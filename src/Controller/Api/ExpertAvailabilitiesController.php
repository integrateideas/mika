<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\NotFoundException;
use Cake\Core\Exception\Exception;
use Cake\I18n\Date;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Stripe\Stripe;
use Cake\I18n\FrozenTime;
use App\Controller\AppHelper;
use Cake\Log\Log;

/**
 * Availabilities Controller
 *
 * @property \App\Model\Table\AvailabilitiesTable $Availabilities
 *
 * @method \App\Model\Entity\Availability[] paginate($object = null, array $settings = [])
 */
class ExpertAvailabilitiesController extends ApiController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        $userId = $this->Auth->user('id');
        
        $this->loadModel('Experts');
        $expert_id = $this->Experts->findByUserId($userId)->first()->get('id');

        $date = new FrozenTime('today');
        $startdate = $date->modify('00:05:00');
        $enddate = $date->modify('23:55:00');

        $expertAvailabilities = $this->ExpertAvailabilities->find()
                                                   ->where(['expert_id' => $expert_id,'status' => 1])
                                                   ->where(function ($exp) use ($startdate, $enddate) {
                                                      return $exp
                                                        ->between('available_from', $startdate, $enddate);
                                                          })
                                                   ->contain(['Experts'])
                                                   ->all();

        $success = true;
        
        $this->set('data',$expertAvailabilities);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }

    public function indexAll()
    {
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        
        $expertAvailabilities = $this->ExpertAvailabilities->find()
                                                           ->contain(['Experts'])
                                                           ->all();

        $success = true;
        
        $this->set('data',$expertAvailabilities);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }

    //TODO: add time constraints
    public function activeAvailabilities()
    {
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $expertAvailabilities = $this->ExpertAvailabilities->find()
                                                     ->contain(['Experts'])
                                                     ->where(['status' => 1])
                                                     ->all();

        $this->set(compact('expertAvailabilities'));
        $this->set('_serialize', ['expertAvailabilities']);
    }

    /**
     * View method
     *
     * @param string|null $id Availability id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $expertAvailabilities = $this->ExpertAvailabilities->get($id, [
            'contain' => ['Experts']
        ]);

        $success = true;
        
        $this->set('data',$expertAvailabilities);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {   
        $userId = $this->Auth->user('id');
        
        $this->loadModel('Experts');
        $this->request->data['expert_id'] = $this->Experts->findByUserId($userId)->first()->get('id');
        
        if (!$this->request->is(['post'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        if ($this->request->data['available_from'] >= $this->request->data['available_to']) {
          throw new Exception("Available-To should be greater than Available-From.");
        }
        $this->request->data['available_from'] =  new \DateTime($this->request->data['available_from']);
        $this->request->data['available_to']  = new \DateTime($this->request->data['available_to']);
        $this->request->data['status'] = 1; 
        $expertAvailabilities = $this->ExpertAvailabilities->newEntity();
        $expertAvailabilities = $this->ExpertAvailabilities->patchEntity($expertAvailabilities, $this->request->getData());

        if (!$this->ExpertAvailabilities->save($expertAvailabilities)) {
        
            throw new Exception("Availability could not be saved.");
        }
        Log::write('debug', '$expertAvailabilities');
        Log::write('debug', $expertAvailabilities);
        $success = true;

        // $appHelper = new AppHelper();
        // $getNotificationContent = $appHelper->getNotificationText('confirm_schedule');

        // if(!empty($getNotificationContent)){

        //     $this->loadComponent('FCMNotification');
        //     $this->loadModel('Users');
        //     $deviceToken = $this->Users->UserDeviceTokens->findByUserId($this->Auth->user('id'))->all()->extract('device_token')->toArray();
        //     Log::write('debug', $deviceToken);
        //     if(!empty($deviceToken)){
        //         $title = $getNotificationContent['title'];
        //         $body = $getNotificationContent['body'];
        //         $data = ['notificationType' => $getNotificationContent['title']];
        //         $notification = $this->FCMNotification->sendToExpertApp($title, $body, $deviceToken, $data);
        //         Log::write('debug', $notification);
        //     }else{
        //         throw new NotFoundException(__('Device token has not been found for this User.'));
        //     }
            
        // }else{
        //     throw new Exception("Error Processing Request. Notification Content is not available.");
        // } 

        $this->set('data',$expertAvailabilities);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Availability id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if (!$this->request->is(['patch', 'post', 'put'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $expertAvailabilities = $this->ExpertAvailabilities->get($id, [
            'contain' => []
        ]);

        if(isset($this->request->data['available_from']) && !empty($this->request->data['available_from']) && isset($this->request->data['available_to']) && !empty($this->request->data['available_to'])){
          
            if ($this->request->data['available_from'] >= $this->request->data['available_to']) {
              throw new Exception("Available-To should be greater than Available-From.");
            }

            $this->request->data['available_from'] =  new \DateTime($this->request->data['available_from']);
            $this->request->data['available_to'] =  new \DateTime($this->request->data['available_to']); 
        }

        $expertAvailabilities = $this->ExpertAvailabilities->patchEntity($expertAvailabilities, $this->request->getData());
        if (!$this->ExpertAvailabilities->save($expertAvailabilities)) {
            throw new Exception("Expert Availability could not be edited.");
        }
      
        $success = true;

        $this->set('data',$expertAvailabilities);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Availability id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if (!$this->request->is(['patch', 'post', 'put','delete'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $expertAvailabilities = $this->ExpertAvailabilities->get($id);
        
        if (!$this->ExpertAvailabilities->delete($expertAvailabilities)) {
            throw new Exception("Availability could not be deleted.");
        } 
        
        $success = true;
        
        $this->set(compact('success'));
        $this->set('_serialize', ['success']);
    }

}
