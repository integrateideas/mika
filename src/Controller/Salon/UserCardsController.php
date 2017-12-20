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

                return $this->redirect(['controller' => 'Users','action' => 'index']);
            }
            $this->Flash->error(__('The user card could not be saved. Please, try again.'));
        }
        
        $this->set(compact('userCards', 'currentYear'));
        $this->set('_serialize', ['userCards']);
    }

}
