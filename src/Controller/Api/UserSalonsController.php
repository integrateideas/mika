<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Core\Exception\Exception;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\I18n\FrozenTime;
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
        $userSalons = $this->UserSalons->find()->all();

        $this->set(compact('userSalons'));
        $this->set('_serialize', ['userSalons']);
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
        
        $this->set('userSalon', $userSalon);
        $this->set('_serialize', ['userSalon']);
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
        $zipcode = '%'.$zipcode.'%';
        
        $getSearchSalons = $this->UserSalons->find()
                                            ->where(['zipcode LIKE' => $zipcode])
                                            ->contain(['Users.Experts' =>function($q)use($whereCond,$serviceId){
                                              return $q->contain(['ExpertAvailabilities'=> function($x) use($whereCond){
                                                return $x->where($whereCond);
                                              },'ExpertSpecializationServices' => function($y) use($serviceId){
                                                return $y->where(['specialization_service_id' => $serviceId]);
                                              }]);
                                            }])
                                            ->matching('Users.Experts.ExpertAvailabilities', function($q) use ($whereCond){
                                                return $q->where($whereCond);
                                            })
                                            ->all()
                                            ->toArray();
        
        $this->set(compact('getSearchSalons'));
        $this->set('_serialize', ['getSearchSalons']);
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
    }
}
