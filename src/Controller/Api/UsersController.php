<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Core\Exception\Exception;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Auth\DefaultPasswordHasher;
use Firebase\JWT\JWT;
use Cake\Utility\Security;
use Cake\I18n\Time;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\Collection\Collection;

/**
 * Users Controller
 *
 *
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class UsersController extends ApiController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['add','login','socialLogin','socialSignup']);
    }

    public function addCard(){
      if (!$this->request->is(['post'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $data = $this->request->getData();
      
      if(!isset($data['stripeJsToken']) || !$data['stripeJsToken']){
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }

      $userId = $this->Auth->user('id');
      if(!$userId){
        throw new NotFoundException(__('We cant identify the user.'));
      }
      $this->loadModel('Experts');
      $expertId = $this->Experts->findByUserId($userId)->first()->id;
      if(!$expertId){
        throw new NotFoundException(__('No entry found in expert table for this user.'));
      }
      $this->loadComponent('Stripe');
      $stripeData = $this->Stripe->addCard($userId,$data['stripeJsToken']);
      
      $stripeData = $stripeData['stripe_data'];
      $data = [
                'user_id'=> $userId,
                'expert_id' => $expertId,
                'stripe_customer_id' => $stripeData->customer,
                'stripe_card_id' => $stripeData->id,
                'status' => 1
              ];
              
      $this->loadModel('UserCards');
      $userCards = $this->UserCards->newEntity();
      $userCards = $this->UserCards->patchEntity($userCards,$data);

      if (!$this->UserCards->save($userCards)) {  
        if($userCards->errors()){
          $this->_sendErrorResponse($userCards->errors());
        }
        throw new Exception("Error Processing Request");
      }
        

      $this->set('data',$userCards);
      $this->set('status',true);
      $this->set('_serialize', ['status','data']);
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
        // $token = $this->request->header('Authorization');
        // $token = explode(' ', $token);
        // $payload = JWT::decode($token[1], Security::salt(), array('HS'));
        $users = $this->Users->find()->contain(['Roles'])->all();

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $user = $this->Users->get($id, [
            'contain' => []
        ]);

        $this->set('data', $user);
        $this->set('status', true);
        $this->set('_serialize', ['status','data']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {     

        if(!$this->request->is(['post'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        $data = $this->request->getData();
        if(!isset($data['first_name']) || !$data['first_name']){
            throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING',"First Name"));
        }
        if(!isset($data['last_name']) || !$data['last_name']){
            throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING',"Last Name"));
        }
        if(!isset($data['phone']) || !$data['phone']){
            throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING',"Phone Number"));
        }
        if(!isset($data['password']) || !$data['password']){
            throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING',"Password"));
        }
        // if(!isset($data['uid']) || !$data['uid']){
        //     throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING',"Facebook Identifier"));
        // }
        if(isset($data['email']) && $data['email']){
          $data['username'] = $data['email'];
        }
        if(!isset($data['username']) || !$data['username']){
            throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING',"Username"));

        }

        if(isset($this->request->data['uid']) && $this->request->data['uid']){
          $data['social_connections'][] = [
                                            'fb_identifier' => $this->request->data['uid'],
                                            'status' => 1
                                          ];
        }
        $data['role_id'] = 3;
        $data['experts'][] = ['timezone' => isset($data['timezone'])? $data['timezone']:'UTC'];
        
        $user = $this->Users->newEntity();
        $user = $this->Users->patchEntity($user, $data, ['associated' => ['Experts','SocialConnections']]);
        if (!$this->Users->save($user)) {
          Log::write('debug',$user); 
          if($user->errors()){
            $this->_sendErrorResponse($user->errors());
          }
          throw new Exception("Error Processing Request");
        }
        Log::write('debug',$user); 
        $this->set('data',$user);
        $this->set('status',true);
        $this->set('_serialize', ['status','data']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {

        if(!$this->request->is(['post'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        
        if($this->Auth->user('role_id') == 1 ){
          if(!$id){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING','id')); 
          }
        }else{
          $id = $this->Auth->user('id');
        }
        
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
              
        $user = $this->Users->patchEntity($user, $this->request->getData());
        
        if (!$this->Users->save($user)) {
            throw new Exception("User edits could not be saved.");
        }
        
        $this->set('data',$user);
        $this->set('status',true);
        $this->set('_serialize', ['status','data']);
    }

    public function linkUserWithFb(){

      if(!$this->request->is(['post'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $this->loadModel('SocialConnections');
      $existingUser = $this->SocialConnections->findByUserId($this->Auth->user('id'))->first();

      if ($existingUser) {
          throw new Exception("User already linked with Facebook.");
      }

      $existingFbAccount = $this->SocialConnections->findByFbIdentifier($this->request->data['uid'])->first();

      if($existingFbAccount){
        throw new Exception("This fb account is already linked with another user.");
      }

      $data = [
                'user_id' => $this->Auth->user('id'),
                'fb_identifier' => $this->request->data['uid'],
                'status' => 1
              ];
     
      $newEntity = $this->SocialConnections->newEntity();
      $updateUser = $this->SocialConnections->patchEntity($newEntity, $data);
      
      if (!$this->SocialConnections->save($updateUser)) {
          
          if($updateUser->errors()){
            $this->_sendErrorResponse($updateUser->errors());
          }
          throw new Exception("Error Processing Request");
        }
        
      $success = true;

      $this->set('data',$updateUser);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
      
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    // public function delete($id = null)
    // {
    //     if($this->Auth->user('role_id') != 1){
    //         throw new UnauthorizedException("You're Not alloweed to access this method.");
    //     }

    //     $this->request->allowMethod(['post', 'delete']);
    //     $user = $this->Users->get($id);
    //     if ($this->Users->delete($user)) {
    //         $this->Flash->success(__('The user has been deleted.'));
    //     } else {
    //         $this->Flash->error(__('The user could not be deleted. Please, try again.'));
    //     }

    //     return $this->redirect(['action' => 'index']);
    // }

    // public function socialSignup($reqData){

    //       $displayName = preg_split('/\s+/', $reqData['displayName']);
          
    //       $data = [
    //                   'first_name' => $displayName[0],
    //                   'last_name' => $displayName[1],
    //                   'email' => ($reqData['email'])?$reqData['email']:'',
    //                   'phone' => ($reqData['phoneNumber'])?$reqData['phoneNumber']:'',
    //                   'password' => '123456789',
    //                   'role_id' => 3,
    //                   'username' => $reqData['email']
    //               ];
    //       $data['social_connections'][] = [
    //                                       'fb_identifier' => $this->request->data['uid'],
    //                                       'status' => 1
    //                                     ];
    //       $data['experts'] = [[]];
    //       $user = $this->Users->newEntity();
    //       $user = $this->Users->patchEntity($user, $data, ['associated' => ['Experts','SocialConnections']]);

    //       if (!$this->Users->save($user)) {
            
    //         if($user->errors()){
    //           $this->_sendErrorResponse($user->errors());
    //         }
    //         throw new Exception("Error Processing Request");
    //       }

    //     return $user->id;
    // }

    public function socialLogin(){

      if (!$this->request->is(['post'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      Log::write('debug', $this->request->data); 
      $this->loadModel('SocialConnections');
      $socialConnection = $this->SocialConnections->find()->where(['fb_identifier' => $this->request->data['uid']])->first();

      if (!$socialConnection) {
        throw new NotFoundException(__('LOGIN_FAILED'));
      }


      // if(!$socialConnection){
      //   $userId = $this->socialSignup($this->request->data);

      // }else{
      //   $userId = $socialConnection->user_id;
      // }
      $user = $this->Users->findById($socialConnection->user_id)->first();
      Log::write('debug',$user);
      $userId = $user->id;

      $data =array();            
      $return = $this->Users->loginExpertInfo($userId,$data);
      
      if (!$return) {
        throw new NotFoundException(__('LOGIN_FAILED'));
      }

      $data = $return['data'];
      $user = $return['user'];

      $time = time() + 10000000;
      $expTime = Time::createFromTimestamp($time);
      $expTime = $expTime->format('Y-m-d H:i:s');

      $data['status']=true;
      $data['data']['user']=$user;
      $data['data']['token']=JWT::encode([
        'sub' => $userId,
        'exp' =>  $time,
        'expert_id'=>$user['experts'][0]['id'],
        ],Security::salt());
      $data['data']['expires']=$expTime;

      $this->set('data',$data['data']);
      $this->set('status',$data['status']);
      $this->set('_serialize', ['status','data']);

    }

    public function login(){
     
      if (!$this->request->is(['post'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $data =array();
      $user = $this->Auth->identify();
      if (!$user) {
        throw new NotFoundException(__('LOGIN_FAILED'));
      }

      $return = $this->Users->loginExpertInfo($user['id'], $data);

      $data = $return['data'];
      $user = $return['user'];

      $time = time() + 10000000;
      $expTime = Time::createFromTimestamp($time);
      $expTime = $expTime->format('Y-m-d H:i:s');
      $data['status']=true;
      $data['data']['user']=$user;
      $data['data']['token']=JWT::encode([
        'sub' => $user['id'],
        'exp' =>  $time,
        'expert_id'=>$user['experts'][0]['id'],
        ],Security::salt());
      $data['data']['expires']=$expTime;
      $this->set('data',$data['data']);
      $this->set('status',$data['status']);
      $this->set('_serialize', ['status','data']);
    }

    public function refreshUserInfo(){

      if (!$this->request->is(['get'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }

      $data =array();
      $return = $this->Users->loginExpertInfo($this->Auth->user('id'), $data);

      $data = $return['data']['data'];
      $data['user'] = $return['user'];

      $this->set('data', $data);
      $this->set('status',true);
      $this->set('_serialize', ['data','status']);
    }

    public function addPhone(){

      if (!$this->request->is(['post'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }

      if(!isset($this->request->data['phone']) || !$this->request->data['phone']){
        throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING',"phone"));  
      }

      $user = $this->Users->findById($this->Auth->user('id'))
                          ->first();

      $user->phone = $this->request->data['phone'];
      if (!$this->Users->save($user)) {
            
        if($user->errors()){
          $this->_sendErrorResponse($user->errors());
        }
        throw new Exception("Error Processing Request");
      }

      $this->set('data', $user);
      $this->set('status',true);
      $this->set('_serialize', ['data','status']);
    }

    public function testFCM(){

      $this->loadComponent('FCMNotification');

      $deviceToken = $this->Users->UserDeviceTokens->findByUserId($this->Auth->user('id'))->first()->device_token;
      // pr($deviceToken); die;
      $title = 'Howdy';
      $body = 'this is a test notification';
      $data = ['hi' => 'hello'];
      $notification = $this->FCMNotification->sendToExpertApp($title, $body, $deviceToken, $data);
    }

}
