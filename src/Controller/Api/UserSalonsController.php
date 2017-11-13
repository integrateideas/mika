<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Core\Exception\Exception;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\I18n\FrozenTime;
use Cake\I18n\FrozenDate;
use Cake\Log\Log;


/**
 * UserSalons Controller
 *
 * @property \App\Model\Table\UserSalonsTable $UserSalons
 *
 * @method \App\Model\Entity\UserSalon[] paginate($object = null, array $settings = [])
 */
class UserSalonsController extends ApiController
{

    public function initialize()
    {
        parent::initialize();
    }
    
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
        $data = $this->UserSalons->find()->all();

        $this->set(compact('data'));
        $this->set('status',true);
        $this->set('_serialize', ['status','data']);
    }

    /**
     * View method
     *
     * @param string|null $id User Salon id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        $this->loadModel('UserSalons');
        $userSalon = $this->UserSalons->get($id, [
            'contain' => []
        ]);
        
        $this->set('data', $userSalon);
        $this->set('_serialize', ['data']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {   
      Log::write('debug', $this->Auth->user());

        if(!$this->request->is(['post'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $userSalon = $this->UserSalons->newEntity();
        $this->request->data['user_id'] = $this->Auth->user('id');
        $this->request->data['status'] = 1;
        
        $userSalon = $this->UserSalons->patchEntity($userSalon, $this->request->data);
        if (!$this->UserSalons->save($userSalon)) {
          
          if($userSalon->errors()){
            $this->_sendErrorResponse($userSalon->errors());
          }
          throw new Exception("Error Processing Request");
        }
        
        $this->request->data['experts'] = [
                                            "user_salon_id" => $userSalon['id']
                                          ];
        $this->loadModel('Experts');
        $experts = $this->Experts->findByUserId($this->Auth->user('id'))->first();
        $experts = $this->Experts->patchEntity($experts, $this->request->data['experts']);
        if (!$this->Experts->save($experts)) {
          
          if($experts->errors()){
            $this->_sendErrorResponse($experts->errors());
          }
          throw new Exception("Error while updating user_salon_id in Experts");
        }
        
        $success = true;
        $this->set('data',$userSalon);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }

    public function searchSalon(){
        if(!$this->request->is(['get'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        if(!isset($this->request->query['zipcode'])){
          throw new BadRequestException(__('MANDATORY_FIELD_MISSING','zipcode')); 
        }

        $time = null;
        $serviceId = null;
        $whereCond = [];
        
        if(isset($this->request->query['time']) && !in_array($this->request->query['time'], ["", null, false])){
            $time = $this->request->query['time'];
            $time  = new FrozenTime($time);
            $whereCond = ['ExpertAvailabilities.available_from <=' => $time, 'ExpertAvailabilities.available_to >=' => $time];
        }

        if(isset($this->request->query['specialization_service_id']) && ($this->request->query['specialization_service_id'])){
              $serviceId = $this->request->query['specialization_service_id'];
            
        }

        $zipcode = $this->request->query['zipcode'];
        $date = new FrozenTime('today');
        $startdate = $date->modify('00:05:00');
        $enddate = $date->modify('23:55:00');

        $this->loadModel('Experts');
        $response = $this->Experts->find()
                                  // ->contain(['ExpertAvailabilities'])
                                  // ->where(function ($exp) use ($startdate, $enddate) {
                                  //                 return $exp->between('ExpertAvailabilities.available_from', $startdate, $enddate);
                                  //                 })
                                      ->matching('Users.UserSalons', function ($q) use ($zipcode) {
                                            return $q->where(['zipcode' => $zipcode]);
                                        })
                                      ->matching('ExpertSpecializationServices', function ($q) use ($serviceId) {
                                            return $q->where(['specialization_service_id' => $serviceId]);
                                        })
                                      ->contain(['Users','ExpertSpecializationServices','ExpertAvailabilities' => function($q) use ($whereCond, $startdate, $enddate){
                                            return $q->where($whereCond)
                                                    ->where(['status' => 1])
                                                    ->where(function ($exp) use ($startdate, $enddate) {
                                                  return $exp->between('available_from', $startdate, $enddate);
                                                });
                                        }])
                                      ->where([''])
                                      ->all()
                                      ->toArray();

        $this->set('data',$response);
        $this->set('status',true);
        $this->set('_serialize', ['status','data']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User Salon id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if(!$this->request->is(['post','put'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $userSalon = $this->UserSalons->get($id, [
            'contain' => []
        ]);
        $this->request->data['user_id'] = $this->Auth->user('id');
        $userSalon = $this->UserSalons->patchEntity($userSalon, $this->request->data);
        
        if (!$this->UserSalons->save($userSalon)) {
            throw new Exception("The user salon could not be saved.");
        }
        $success = true;

        $this->set('data',$userSalon);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User Salon id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $userSalon = $this->UserSalons->get($id);
        if (!$this->UserSalons->delete($userSalon)) {
            throw new Exception("The user salon could not be deleted.");
        }

        $this->set('status',true);
        $this->set('_serialize', ['status','data']);
    }
}
