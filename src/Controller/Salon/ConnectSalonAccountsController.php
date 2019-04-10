<?php
namespace App\Controller\Salon;

use App\Controller\Salon\AppController;

/**
 * ConnectSalonAccounts Controller
 *
 * @property \App\Model\Table\ConnectSalonAccountsTable $ConnectSalonAccounts
 *
 * @method \App\Model\Entity\ConnectSalonAccount[] paginate($object = null, array $settings = [])
 */
class ConnectSalonAccountsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */

    public function stripeAccountConnect(){

        $this->loadModel('UserSalons');
        $userSalon = $this->UserSalons->findByUserId($this->Auth->user('id'))->first();
        if(!$userSalon){
            throw new NotFoundException(__('No Salon has been setup for this Salon Owner. Please setup your Salon first.'));
        }
        $code = $this->request->query['code'];
        if($code){
            $data = $this->loadComponent('Stripe')->ConnectAccount($code);
            if(isset($data['stripe_user_id'])){

                $data['user_salon_id'] = $userSalon->id;
                $data['stripe_user_account_id'] = $data['stripe_user_id'];
                $connectSalonAccount = $this->ConnectSalonAccounts->newEntity();
                $connectSalonAccount = $this->ConnectSalonAccounts->patchEntity($connectSalonAccount, $data);

                if ($this->ConnectSalonAccounts->save($connectSalonAccount)) {
                    $this->Flash->success(__('The connect salon account has been saved.'));

                    return $this->redirect(['controller' => 'connectSalonAccounts','action' => 'index']);
                }
                $this->Flash->error(__('The connect salon account could not be saved. Please, try again.'));
            }elseif($data['error']){
                $this->Flash->error(__('Salon Account has not been connected on stripe. Invalid Grant'));

                return $this->redirect(['controller' => 'connectSalonAccounts','action' => 'index']);
            }

            $this->set(compact('connectSalonAccount'));
            $this->set('_serialize', ['connectSalonAccount']);
        }else{
            throw new NotFoundException(__('Stripe Account Connect Code not found.'));
        }

    }

    public function index()
    {
        $connectSalonAccounts = $this->ConnectSalonAccounts->find()->contain(['UserSalons'])->all()->toArray();
        $reqData = [];
        foreach ($connectSalonAccounts as $connectSalonAccount) {
            $getAccountDetails = $this->loadComponent('Stripe')->RetrieveAccountDetails($connectSalonAccount->stripe_user_account_id);
            if(!empty($getAccountDetails)){
                $reqData = $getAccountDetails['external_accounts']['data'];
            }
        }
        $this->set(compact('reqData'));
        $this->set('_serialize', ['reqData']);
    }

    /**
     * View method
     *
     * @param string|null $id Connect Salon Account id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $connectSalonAccount = $this->ConnectSalonAccounts->get($id, [
            'contain' => ['StripeUserAccounts', 'UserSalons']
        ]);

        $this->set('connectSalonAccount', $connectSalonAccount);
        $this->set('_serialize', ['connectSalonAccount']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $connectSalonAccount = $this->ConnectSalonAccounts->newEntity();
        if ($this->request->is('post')) {
            $connectSalonAccount = $this->ConnectSalonAccounts->patchEntity($connectSalonAccount, $this->request->getData());
            if ($this->ConnectSalonAccounts->save($connectSalonAccount)) {
                $this->Flash->success(__('The connect salon account has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The connect salon account could not be saved. Please, try again.'));
        }
        $stripeUserAccounts = $this->ConnectSalonAccounts->StripeUserAccounts->find('list', ['limit' => 200]);
        $userSalons = $this->ConnectSalonAccounts->UserSalons->find('list', ['limit' => 200]);
        $this->set(compact('connectSalonAccount', 'stripeUserAccounts', 'userSalons'));
        $this->set('_serialize', ['connectSalonAccount']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Connect Salon Account id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $connectSalonAccount = $this->ConnectSalonAccounts->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $connectSalonAccount = $this->ConnectSalonAccounts->patchEntity($connectSalonAccount, $this->request->getData());
            if ($this->ConnectSalonAccounts->save($connectSalonAccount)) {
                $this->Flash->success(__('The connect salon account has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The connect salon account could not be saved. Please, try again.'));
        }
        $stripeUserAccounts = $this->ConnectSalonAccounts->StripeUserAccounts->find('list', ['limit' => 200]);
        $userSalons = $this->ConnectSalonAccounts->UserSalons->find('list', ['limit' => 200]);
        $this->set(compact('connectSalonAccount', 'stripeUserAccounts', 'userSalons'));
        $this->set('_serialize', ['connectSalonAccount']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Connect Salon Account id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $connectSalonAccount = $this->ConnectSalonAccounts->get($id);
        if ($this->ConnectSalonAccounts->delete($connectSalonAccount)) {
            $this->Flash->success(__('The connect salon account has been deleted.'));
        } else {
            $this->Flash->error(__('The connect salon account could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
