<?php
namespace App\Controller\Salon;

use App\Controller\Salon\AppController;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Event\Event;
use Cake\I18n\Date;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class UserCardsController extends AppController
{

    /**
     * Add Card method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $userCards = $this->UserCards->newEntity();
        $date = Date::now();
        $currentYear = $date->year - 2000;
        if ($this->request->is('post')) {

            $data = $this->request->getData();
            if(!isset($data['stripe_token']) || !$data['stripe_token']){
                throw new MethodNotAllowedException(__('BAD_REQUEST'));
            }

            $userId = $this->Auth->user('id');

            $this->loadComponent('Stripe');
            $stripeData = $this->Stripe->addCard($userId,$data['stripe_token']);
            
            $stripeData = $stripeData['stripe_data'];

            $data = [
                        'user_id'=> $userId,
                        'stripe_customer_id' => $stripeData->customer,
                        'stripe_card_id' => $stripeData->id,
                        'status' => 1
                    ];
            $userCards = $this->UserCards->patchEntity($userCards,$data);
            if ($this->UserCards->save($userCards)) {

                $this->Flash->success(__('The user card has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user card could not be saved. Please, try again.'));
        }
        
        $this->set(compact('userCards', 'currentYear'));
        $this->set('_serialize', ['userCards']);
    }

    public function index(){

        $this->loadComponent('Stripe'); 
        $userId = $this->Auth->user('id');
        $data = $this->Stripe->listCards($userId);
        
        foreach ($data['data'] as $key => $value) {
            $getUserCards = $this->UserCards->find()
                                            ->where(['stripe_customer_id' => $value['customer']])
                                            ->all()
                                            ->indexBy('stripe_card_id')
                                            ->toArray();
        }
        $this->set('status',$data['status']);
        $this->set('data',$data['data']);
        $this->set('getUserCards',$getUserCards);
        $this->set('_serialize', ['status','data','getUserCards']);
    }

    public function delete($id){
        
        $this->loadModel('UserCards');
        $getCardDetails = $this->UserCards->findById($id)
                                          ->first();
        
        if(!$getCardDetails){
           throw new NotFoundException(__('We cant identify the card for this user.'));
        }
        if(!isset($getCardDetails->stripe_card_id) || !$getCardDetails->stripe_card_id){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        $userId = $this->Auth->user('id');


        $stripeCustomerId = $getCardDetails->stripe_customer_id;
        $status = false;
        $this->loadComponent('Stripe');
        $data = $this->Stripe->deleteCard($getCardDetails->stripe_card_id,$stripeCustomerId);
        if(!empty($getCardDetails)){
            if (!$this->UserCards->delete($getCardDetails)){
              throw new Exception("Error Processing Request");
            }
        }
        $this->Flash->success(__('The card has been deleted successfully.'));
        return $this->redirect(['action' => 'index']);
    }

}
