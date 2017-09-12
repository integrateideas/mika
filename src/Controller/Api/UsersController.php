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

        if(!isset($data['role_id'])){
            throw new Exception("No role provided for the user.");
        }

        if($data['role_id'] == 3){
            $data['experts'] = [[]];
            $user = $this->Users->patchEntity($user, $data, ['associated' => 'Experts']);
        }else{
            $user = $this->Users->patchEntity($user, $data);
        }
        if (!$this->Users->save($user)) {

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
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
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

        if(!$this->request->is(['post'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $email = $this->request->data('email');
        $pwd = $this->request->data('password');

        if(!$email){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING','email'));
        }
        
        if(!$pwd){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING','password'));
        }

        $this->loadModel('Users');
        $user = $this->Users->find()->where(['email' => $email])->contain(['Experts'])->first();
        $token=null;
        $success=false;

        if($user != null &&  (new DefaultPasswordHasher)->check($pwd, $user['password'])){
        
            $token = JWT::encode([
                     'id' => $user['id'],
                     'sub' => $user['id']
                    ],Security::salt());
            $success = true;

        }else{
            throw new NotFoundException(__('ENTITY_DOES_NOT_EXISTS','User'));
        }

        $this->request->session()->write('User',$user->toArray()); 

        $this->set([
           'success' => $success,
           'data' => [
               'token' =>  $token
           ],
           '_serialize' => ['success', 'data']
        ]);
       
   }

}
