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
        $this->Auth->allow(['add','login']);
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
        pr($this->Auth->user());die;
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

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
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

        $user = $this->Users->newEntity();
        $data = $this->request->getData();
        
        if(isset($data['email']) && $data['email']){
          $data['username'] = $data['email'];
        }
        $data['role_id'] = 3;
        $data['experts'] = [[]];

        $user = $this->Users->patchEntity($user, $data, ['associated' => 'Experts']);
        
        if (!$this->Users->save($user)) {
          
          if($user->errors()){
            $this->_sendErrorResponse($user->errors());
          }
          throw new Exception("Error Processing Request");
        }
        
        $success = true;

        $this->set(compact('user','success'));
        $this->set('_serialize', ['user','success']);
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

        $user = $this->Users->get($id, [
            'contain' => []
        ]);
            
        $user = $this->Users->patchEntity($user, $this->request->getData());
        
        if (!$this->Users->save($user)) {
            throw new Exception("User edits could not be saved.");
        }
        
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if($this->Auth->user('role_id') != 1){
            throw new UnauthorizedException("You're Not alloweed to access this method.");
        }

        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function login(){
     
      if (!$this->request->is(['post'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      // pr($this->request->data()); die('here');
      // pr($this->request);
      $data =array();
      $user = $this->Auth->identify();
      if (!$user) {
        throw new NotFoundException(__('LOGIN_FAILED'));
      }
      $user = $this->Users->find()
                            ->where(['id' => $user['id']])
                            ->contain(['Experts.ExpertSpecializations.ExpertSpecializationServices'])
                            ->first();
      
      if(isset($user['experts']) && $user['experts'] != []){  
        $data['data']['expertSpecializations'] = $user['experts'][0]['expert_specializations'];
      }

        $time = time() + 10000000;
        $expTime = Time::createFromTimestamp($time);
        $expTime = $expTime->format('Y-m-d H:i:s');
        $data['status']=true;
        $data['data']['id']=$user;
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

    public function addCard(){

      if (!$this->request->is(['post'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }

      $data = $this->request->getData();

      if(!$data->stripeJsToken){
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }

      \Stripe\Stripe::setApiKey(Configure::read('StripeTestKey'));

      $userExpert = $this->Users->findById(5)
                                  ->contain('Experts.ExpertCards')
                                  ->first();

      if(!isset($userExpert->experts[0]->expert_cards[0])){
      
        //when the user is NOT registered on stripe.
        
        try {
              
              $customer = \Stripe\Customer::create([
                "description" => "Customer for sofia.moore@example.com",
                "source" => $data->stripeJsToken // obtained with Stripe.js
              ]);
              pr($customer); die;
          } catch (Exception $e) {
              pr($e); die;
          }  
      
      }else{
      
        //when the user is already registered on stripe.

        $stripeCusId = $userExpert->experts[0]->expert_cards[0]->stripe_customer_id;
        
        try {
              
            $customer = \Stripe\Customer::retrieve($stripeCusId);
            $customer->sources->create(["source" => $data->stripeJsToken]);
              pr($customer); die;
          } catch (Exception $e) {
              pr($e); die;
          }
      
      }

    }

}
