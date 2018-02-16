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

    public function stripeAccountConnect(){

        $userSalon = $this->UserSalons->findByUserId($this->Auth->user('id'))->first();
        if(!$userSalon){
            throw new NotFoundException(__('No Salon has been setup for this Salon Owner. Please setup your Salon first.'));
        }
        $code = $this->request->query['code'];
        if(!$code){
          throw new NotFoundException(__('Account Code not found.'));  
        }
        $data = $this->loadComponent('Stripe')->ConnectAccount($code);
        $data = [
                    'access_token' => 'sk_test_r3g7yChO7wtTPJTgQwLzKsBj',
                    'refresh_token' => 'rt_CKPwdIjN7uKPf8CBDKd7mtAzZmtHs7FxNQIjtjEHcUd7ZGow',
                    'token_type' => 'bearer',
                    'stripe_publishable_key' => 'pk_test_lnYhz7U6J9W6of4BLxkIkeKz',
                    'stripe_user_id' => 'acct_1Bvl4vJ6kzH87Aay',
                    'scope' => 'express'
                ];
    
        if(isset($data['stripe_user_id'])){

            $data['user_salon_id'] = $userSalon->id;

        }

        pr($data);die;
        if(empty($data)){
        }


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
        $userId = $this->Auth->user('id');
        
        $this->loadModel('UserCards');
        $userCards = $this->UserCards->findByUserId($userId)->first();
        if(!$userCards){
            throw new NotFoundException(__('Please setup your card first.'));
        }
        $stripeCustomerId = $userCards->stripe_customer_id;
        $accountDetail = $this->AccountDetails->newEntity();
        $accountHolderType = ['individual' => 'Individual', 'company' => 'Company'];
        if ($this->request->is('post')) {
            $this->loadModel('UserSalons');
            $userSalon = $this->UserSalons->findByUserId($this->Auth->user('id'))->first();
            if(!$userSalon){
                throw new NotFoundException(__('No Salon has been setup for this Salon Owner. Please setup your Salon first.'));
            }
            $this->loadComponent('Stripe');
            $bankAccountToken = $this->Stripe->createBankAccountToken($this->request->data['account_holder_name'], $this->request->data['account_number'], $this->request->data['routing_number'], $this->request->data['account_holder_type']);
            if(!$bankAccountToken){
              throw new NotFoundException(__('Unable to create bank Account Token. Please try again later.'));
            }
            $customerBankAccount = $this->Stripe->createBankAccount($stripeCustomerId,$bankAccountToken['id']);
            if(!$customerBankAccount){
              throw new NotFoundException(__('Unable to create bank Account. Please try again later.'));   
            }
            $this->request->data['user_salon_id'] = $userSalon->id;
            $this->request->data['stripe_customer_id'] = $customerBankAccount['customer'];
            $this->request->data['stripe_bank_account_id'] = $customerBankAccount['id'];
            $accountDetail = $this->AccountDetails->patchEntity($accountDetail, $this->request->data);
            if ($this->AccountDetails->save($accountDetail)) {
                $this->Flash->success(__('The account detail has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The account detail could not be saved. Please, try again.'));
        }
        $this->set(compact('accountDetail','accountHolderType','userCards'));
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
        $accountHolderType = ['individual' => 'Individual', 'company' => 'Company'];
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
        $this->set(compact('accountDetail','accountHolderType'));
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
