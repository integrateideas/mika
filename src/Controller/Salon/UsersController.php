<?php
namespace App\Controller\Salon;

use App\Controller\Salon\AppController;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Event\Event;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    public function initialize(){
        parent::initialize();

        $this->loadComponent('Flash');
        $this->loadComponent('Auth');
        $this->Auth->allow(['login','logout','signUp']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $users = $this->Users->findById($this->Auth->user('id'))
                             ->contain(['Roles','UserSalons'])
                             ->where(['is_salon_owner' => 1])
                             ->all()
                             ->toArray();

        $this->loadModel('Experts');
        $userSalonId = $users[0]->user_salons[0]->id;
        $getExperts = $this->Experts->findByUserSalonId($userSalonId)
                                    ->contain(['Users.Roles'])
                                    ->all()
                                    ->toArray();
        
        $this->set(compact('users','getExperts'));
        $this->set('_serialize', ['users','getExperts']);
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
        $user = $this->Users->get($id, [
            'contain' => ['Roles', 'Experts']
        ]);
        $this->loadModel('Appointments');
        $appointments = $this->Appointments->findByUserId($id)
                                           ->contain(['AppointmentServices.ExpertSpecializations.Specializations','AppointmentReviews'])
                                           ->order(['created' => 'DESC'])
                                           ->all()
                                           ->toArray();
        
        $this->loadModel('Transactions');
        $this->loadModel('Experts');
        $this->loadModel('ExpertAvailabilities');
        $transactionAmount = null;
        $getUserExpert = null;
        $getAppointmentAvailability = null;
        $services = [];
        foreach ($appointments as $key => $value) {
            $getAppointmentAvailability[$value->id] = $this->ExpertAvailabilities->findById($value->expert_availability_id)->first();
            $transactionAmount[$value->id] = $this->Transactions->findById($value->transaction_id)->first();
            
            $getUserExpert[$value->id] = $this->Experts->Users->findById($value->user_id)->first();
            foreach ($value->appointment_services as $key => $service) {
                $services[$value->id] = $service->expert_specialization->specialization->label;
            }
            
        }
        
        $this->set('getAppointmentAvailability', $getAppointmentAvailability);
        $this->set('transactionAmount', $transactionAmount);
        $this->set('getUserExpert', $getUserExpert);
        $this->set('services', $services);
        $this->set('user', $user);
        $this->set('appointments', $appointments);
        $this->set('_serialize', ['user','appointments']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['username'] = $data['email'];
            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {

                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $roles = $this->Users->Roles->find()->where(['label' => 'User'])->all()->combine('id','label')->toArray();
        
        $this->set(compact('user', 'roles'));
        $this->set('_serialize', ['user']);
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
        $roles = $this->Users->Roles->find()->where(['label' => 'User'])->all()->combine('id','label')->toArray();
        $this->set(compact('user', 'roles'));
        $this->set('_serialize', ['user']);
    }

    public function signUp(){
        $this->viewBuilder()->layout('sign-up');
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['username'] = $data['email'];
            $data['role_id'] = 2;
            $data['is_salon_owner'] = 1;
            $data['status'] = 1;
            $user = $this->Users->patchEntity($user, $data);
            
            if ($this->Users->save($user)) {

                $this->Flash->success(__('Salon Owner has been saved.'));

                $this->_loginUser($user->toArray());
                return;
            }
            $this->Flash->error(__('Salon Owner could not be saved. Please, try again.'));
        }
        
        $roles = $this->Users->Roles->find()
                                    ->where(['label' => 'User'])
                                    ->all()
                                    ->combine('id','label')
                                    ->toArray();
        
        $this->set(compact('user', 'roles'));
        $this->set('_serialize', ['user']);
    }

    private function _loginUser($user){
        if($user['role_id'] == 2){   
            if ($user['is_salon_owner']) {
                    $this->Auth->setUser($user);
                    $isUserSalon = $this->Users->UserSalons->findByUserId($user['id'])->first();
                    
                    if(!$isUserSalon){
                        return $this->redirect(['controller' => 'UserSalons','action' => 'add']);
                    }else{

                        return $this->redirect(['controller' => 'Users','action' => 'index']);
                    }
                }
        }
            $this->Flash->error(__('Invalid username or password, try again'));
    }
    
    public function login()
    {
        $this->viewBuilder()->layout('login-admin');
        
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            $this->_loginUser($user);
        }
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
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }
}
