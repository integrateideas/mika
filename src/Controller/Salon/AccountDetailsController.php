<?php
namespace App\Controller\Salon;

use App\Controller\Salon\AppController;
use Cake\Network\Exception\NotFoundException;

/**
 * AccountDetails Controller
 *
 * @property \App\Model\Table\AccountDetailsTable $AccountDetails
 *
 * @method \App\Model\Entity\AccountDetail[] paginate($object = null, array $settings = [])
 */
class AccountDetailsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $accountDetails = $this->paginate($this->AccountDetails);
        $accountDetails = $this->AccountDetails->find()
                                               ->contain(['UserSalons' => function($q){
                                                            return $q->where(
                                                                ['user_id' => $this->Auth->user('id')]);
                                                }])
                                               ->all()
                                               ->toArray();
        
        $this->set(compact('accountDetails'));
        $this->set('_serialize', ['accountDetails']);
    }

    /**
     * View method
     *
     * @param string|null $id Account Detail id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $accountDetail = $this->AccountDetails->get($id, [
            'contain' => []
        ]);

        $this->set('accountDetail', $accountDetail);
        $this->set('_serialize', ['accountDetail']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $accountDetail = $this->AccountDetails->newEntity();
        if ($this->request->is('post')) {
            $this->loadModel('UserSalons');
            $userSalon = $this->UserSalons->findByUserId($this->Auth->user('id'))->first();
            if(!$userSalon){
                throw new NotFoundException(__('No Salon has been setup for this Salon Owner. Please setup your Salon first.'));
            }
            $this->request->data['user_salon_id'] = $userSalon->id;
            $accountDetail = $this->AccountDetails->patchEntity($accountDetail, $this->request->data);
            if ($this->AccountDetails->save($accountDetail)) {
                $this->Flash->success(__('The account detail has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The account detail could not be saved. Please, try again.'));
        }
        $this->set(compact('accountDetail'));
        $this->set('_serialize', ['accountDetail']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Account Detail id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $accountDetail = $this->AccountDetails->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $accountDetail = $this->AccountDetails->patchEntity($accountDetail, $this->request->getData());
            if ($this->AccountDetails->save($accountDetail)) {
                $this->Flash->success(__('The account detail has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The account detail could not be saved. Please, try again.'));
        }
        $this->set(compact('accountDetail'));
        $this->set('_serialize', ['accountDetail']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Account Detail id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $accountDetail = $this->AccountDetails->get($id);
        if ($this->AccountDetails->delete($accountDetail)) {
            $this->Flash->success(__('The account detail has been deleted.'));
        } else {
            $this->Flash->error(__('The account detail could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
