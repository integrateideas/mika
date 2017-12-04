<?php
namespace App\Controller\Api\User;

use App\Controller\Api\User\ApiController;
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
use Cake\I18n\FrozenTime;
use Cake\I18n\FrozenDate;

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
        $this->Auth->allow(['login','socialLogin','socialSignup','add']);
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
      
      $this->loadComponent('Stripe');
      $stripeData = $this->Stripe->addCard($userId,$data['stripeJsToken']);
      
      $stripeData = $stripeData['stripe_data'];

      $data = [
                'user_id'=> $userId,
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
        
      $userCards['stripeData'] = $stripeData;
      
      $this->set('data',$userCards);
      $this->set('status',true);
      $this->set('_serialize', ['status','data','stripeData']);
    }

    public function deleteCard(){
      if (!$this->request->is(['post', 'delete'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }

      $userId = $this->Auth->user('id');
      if(!$userId){
        throw new NotFoundException(__('We cant identify the user.'));
      }
      $data = $this->request->getData();

      if(!isset($data['stripe_card_id']) || !$data['stripe_card_id']){
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $this->loadModel('UserCards');
      $getCardDetails = $this->UserCards->findByUserId($userId)
                                        ->where(['stripe_card_id' => $data['stripe_card_id']])
                                        ->first();
      
      if(!$getCardDetails){
        throw new NotFoundException(__('We cant identify the card for this user.'));
      }
      
      $stripeCustomerId = $getCardDetails->stripe_customer_id;
      $status = false;
      
      $this->loadComponent('Stripe');
      $data = $this->Stripe->deleteCard($data['stripe_card_id'],$stripeCustomerId);
      if(!empty($data)){

        if (!$this->UserCards->delete($getCardDetails)){
          throw new Exception("Error Processing Request");
        }
        $status = true;
      }
      $response = ['status' => true,'message' => 'Card has been deleted.'];
        
      $this->set('data',$response);
      $this->set('_serialize', ['data']);
    }

     public function listCards($userId = null){

      if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }

      $this->loadComponent('Stripe');
      
      $userId = $this->Auth->user('id');
      if(!$userId){
        throw new NotFoundException(__('We cant identify the user.'));
      }

      $data = $this->Stripe->listCards($userId);
      
      $this->set('status',$data['status']);
      $this->set('data',$data['data']);
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

        $this->set('data',$user);
        $this->set('status',$updateUser);
        $this->set('_serialize', ['status','data']);
      
    }

    // public function socialSignup($reqData){

    //       $displayName = preg_split('/\s+/', $reqData['displayName']);
          
    //       $data = [
    //                   'first_name' => $displayName[0],
    //                   'last_name' => $displayName[1],
    //                   'email' => ($reqData['email'])?$reqData['email']:'',
    //                   'phone' => ($reqData['phoneNumber'])?$reqData['phoneNumber']:'',
    //                   'password' => '123456789',
    //                   'role_id' => 2,
    //                   'username' => $reqData['email']
    //               ];
    //       $data['social_connections'][] = [
    //                                       'fb_identifier' => $this->request->data['uid'],
    //                                       'status' => 1
    //                                     ];
    //       $data['experts'] = [[]];
    //       $user = $this->Users->newEntity();
    //       $user = $this->Users->patchEntity($user, $data, ['associated' => ['SocialConnections']]);

    //         if (!$this->Users->save($user)) {
            
    //         if($user->errors()){
    //           $this->_sendErrorResponse($user->errors());
    //         }
    //         throw new Exception("Error Processing Request");
    //       }

    //     return $user->id;
    // }

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
        $user = $this->Users->newEntity();
        $data = $this->request->getData();
        
        if(isset($data['email']) && $data['email']){
          $data['username'] = $data['email'];
        }
        if(isset($this->request->data['uid']) && $this->request->data['uid']){
          $data['social_connections'][] = [
                                            'fb_identifier' => $this->request->data['uid'],
                                            'status' => 1
                                          ];
        }
        
        $data['role_id'] = 2;
        Log::write('debug',$data);        
     
        $user = $this->Users->patchEntity($user, $data, ['associated' => ['SocialConnections']]);
        
        Log::write('debug',$user);
          
        if (!$this->Users->save($user, ['associated' => ['SocialConnections']])) {
          
          if($user->errors()){
            $this->_sendErrorResponse($user->errors());
          }
          throw new Exception("Error Processing Request");
        }

        Log::write('debug',$user);

        $success = true;

        $this->set('data',$user);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']); 
    }

    public function edit($id = null)
    {

        if(!$this->request->is(['post','put'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        
        $user = $this->Auth->user();
        
        $user = $this->Users->get($user['id'], [
            'contain' => []
        ]);
        
        $user = $this->Users->patchEntity($user, $this->request->getData());
        
        if (!$this->Users->save($user)) {
            throw new Exception("User edits could not be saved.");
        }
        
        $success = true;

        $this->set('data',$user);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']); 
    }

    public function socialLogin(){

      if (!$this->request->is(['post'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      
      $this->loadModel('SocialConnections');
      $socialConnection = $this->SocialConnections->find()->where(['fb_identifier' => $this->request->data['uid']])->first();

      $existingUser = $this->Users->findByEmail($this->request->data['email'])->first();

      if(!$socialConnection){
        
        if(!$existingUser){
          $userId = $this->socialSignup($this->request->data);
        }else{
          $userId = $existingUser->id;
        }

      }else{
        $userId = $socialConnection->user_id;
      }

      $data =array();            
      $user = $this->Users->find()
                          ->where(['id' => $userId])
                          ->contain(['SocialConnections','UserCards','Appointments' => function ($q) use ($userId){
                                return $q->last();
                              }])
                          ->first();

      $favouriteExperts = $this->Users->UserFavouriteExperts->findByUserId($userId)
                                                          ->all()
                                                          ->indexBy('expert_id');


      
      if (!$user) {
        throw new NotFoundException(__('LOGIN_FAILED'));
      }

      $time = time() + 10000000;
      $expTime = Time::createFromTimestamp($time);
      $expTime = $expTime->format('Y-m-d H:i:s');
      
      $data['status']=true;
      $data['data']['user']=$user;
      $data['data']['user']['favouriteExperts']=$favouriteExperts;

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

     public function login(){
     
      if (!$this->request->is(['post'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }

      $data =array();
      $user = $this->Auth->identify();
      if (!$user) {
        throw new NotFoundException(__('LOGIN_FAILED'));
      }
      $userId = $user['id'];
      $user = $this->Users->find()
                            ->where(['id' => $userId])
                            ->contain(['SocialConnections','UserCards','Appointments' => function ($q) use ($userId){
                                return $q->order(['created' => 'DESC'])->limit(1);
                              }])
                            ->first();
                            
      $getUserLastLocation = null;
      if($user->appointments && isset($user->appointments) && $user->appointments[0]){
        $lastAppointmentExpert = $user->appointments[0]->expert_id;
        $this->loadModel('Experts');
        $getUser = $this->Experts->findById($lastAppointmentExpert)->contain(['Users.UserSalons'])->first();

        $getUserLastLocation = $getUser->user->user_salons[0];
      }

      $favouriteExperts = $this->Users->UserFavouriteExperts->findByUserId($user['id'])
                                                    ->all()
                                                    ->indexBy('expert_id');

      $time = time() + 10000000;
      $expTime = Time::createFromTimestamp($time);
      $expTime = $expTime->format('Y-m-d H:i:s');
      $data['status']=true;
      $data['data']['user']=$user;
      $data['data']['user']['favouriteExperts']=$favouriteExperts;
      $data['data']['user']['expertLastLocation']=$getUserLastLocation;
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

    public function refreshUser(){

      if(!$this->request->is(['get'])){
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      
      $user = $this->Auth->identify();
      if (!$user) {
        throw new NotFoundException(__('LOGIN_FAILED'));
      }
      $response = $this->Users->find()
                            ->where(['id' => $user['id']])
                            ->contain(['SocialConnections','UserCards'])
                            ->first();
      
      $response['favouriteExperts'] = $this->Users->UserFavouriteExperts->findByUserId($user['id'])
                                                    ->all()
                                                    ->indexBy('expert_id')
                                                    ->toArray();
                                                          
      $this->set('data',$response);
      $this->set('status',true);
      $this->set('_serialize', ['status','data']);
    }

    public function searchExpert(){
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
                                  ->matching('Users.UserSalons', function ($q) use ($zipcode) {
                                        return $q->where(['zipcode' => $zipcode]);
                                    })
                                  ->matching('ExpertSpecializationServices', function ($q) use ($serviceId) {
                                        return $q->where(['specialization_service_id' => $serviceId]);
                                    })
                                  ->contain(['Users','ExpertSpecializationServices.SpecializationServices','ExpertAvailabilities' => function($q) use ($whereCond, $startdate, $enddate){
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
}
