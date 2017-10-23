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
        $this->Auth->allow(['login','socialLogin','socialSignup']);
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

        $this->set(compact('updateUser','success'));
        $this->set('_serialize', ['updateUser','success']);
      
    }

    public function socialSignup($reqData){

          $displayName = preg_split('/\s+/', $reqData['displayName']);
          
          $data = [
                      'first_name' => $displayName[0],
                      'last_name' => $displayName[1],
                      'email' => ($reqData['email'])?$reqData['email']:'',
                      'phone' => ($reqData['phoneNumber'])?$reqData['phoneNumber']:'',
                      'password' => '123456789',
                      'role_id' => 3,
                      'username' => $reqData['email']
                  ];
          $data['social_connections'][] = [
                                          'fb_identifier' => $this->request->data['uid'],
                                          'status' => 1
                                        ];
          $data['experts'] = [[]];
          $user = $this->Users->newEntity();
          $user = $this->Users->patchEntity($user, $data, ['associated' => ['Experts','SocialConnections']]);

            if (!$this->Users->save($user)) {
            
            if($user->errors()){
              $this->_sendErrorResponse($user->errors());
            }
            throw new Exception("Error Processing Request");
          }

        return $user->id;
    }

    public function socialLogin(){

      if (!$this->request->is(['post'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      
      $this->loadModel('SocialConnections');
      $socialConnection = $this->SocialConnections->find()->where(['fb_identifier' => $this->request->data['uid']])->first();


      if(!$socialConnection){
        $userId = $this->socialSignup($this->request->data);

      }else{
        $userId = $socialConnection->user_id;
      }

      $data =array();            
      $return = $this->Users->loginInfo($userId,$data);

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

      $return = $this->Users->loginInfo($user['id'], $data);
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
}
