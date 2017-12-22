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

    public function getUserSalon(){
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        $location = $this->request->query(['location']);
        $zipcode = $this->request->query(['zipcode']);
        
        if((isset($location) && $location) || (isset($zipcode) && $zipcode)){
        
            if((isset($location) && $location) && $zipcode == 'null'){
                $data = $this->UserSalons->find()
                                         ->where([
                                                    'location' => $location
                                                ])
                                         ->all()
                                         ->toArray();
            }elseif((isset($zipcode) && $zipcode) && $location == 'null'){
            
              $data = $this->UserSalons->find()
                                       ->where([
                                                  'zipcode' => $zipcode
                                              ])
                                       ->all()
                                       ->toArray();
            }elseif((isset($zipcode) && $zipcode) && (isset($location) && $location)){
              $data = $this->UserSalons->find()
                                       ->where([
                                                    'location' => $location,
                                                    'zipcode' => $zipcode
                                                  ])
                                       ->all()
                                       ->toArray();
            }else{
              throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING',"either Location or zipcode"));
            }
            
        }
    
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
        $data = $this->request->data;
        $data['user_id'] = $this->Auth->user('id');
        if(!isset($data['user_salon_id'])){
            if(!isset($data['salon_name']) || !$data['salon_name']){
                throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING',"Salon Name"));

            }
            if(!isset($data['location']) || !$data['location']){
                throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING',"Location"));

            }
            if(!isset($data['zipcode']) || !$data['zipcode']){
                throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING',"Zipcode"));

            }

            $data['status'] = 1;
            Log::write('debug', $data);
            $userSalon = $this->UserSalons->newEntity();
            $userSalon = $this->UserSalons->patchEntity($userSalon, $data);
            
            if (!$this->UserSalons->save($userSalon)) {
              
              if($userSalon->errors()){
                $this->_sendErrorResponse($userSalon->errors());
              }
              throw new Exception("Error Processing Request");
            }
        }else{
            $userSalon = $this->UserSalons->findById($data['user_salon_id'])->first();
        }
        Log::write('debug', $userSalon);
        $this->loadModel('Experts');
        $expertData = $this->Experts->updateAll(    ['user_salon_id' => $userSalon->id], // fields
                                                    ['user_id' => $data['user_id']] // conditions
                                                );
        Log::write('debug', $expertData);
        
        $success = true;
        $this->set('data',$userSalon);
        $this->set('status',$success);
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
