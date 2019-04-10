<?php
namespace App\Controller\Salon;

use App\Controller\Salon\AppController;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Event\Event;

/**
 * Specializations Controller
 *
 * @property \App\Model\Table\SpecializationsTable $Specializations
 *
 * @method \App\Model\Entity\Specialization[] paginate($object = null, array $settings = [])
 */
class UserSalonsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        
        $userSalon = $this->UserSalons->findByUserId($this->Auth->user('id'))
                                      ->contain(['Users'])
                                      ->all()
                                      ->toArray();

        $this->set(compact('userSalon'));
        $this->set('_serialize', ['userSalon']);
    }

    /**
     * View method
     *
     * @param string|null $id Specialization id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $userSalon = $this->UserSalons->get($id, [
            'contain' => ['Users']
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
        $userSalon = $this->UserSalons->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $data['user_id'] = $this->Auth->user('id');
            $userSalon = $this->UserSalons->patchEntity($userSalon, $data);
            if ($this->UserSalons->save($userSalon)) {
                $this->Flash->success(__('The user salon has been saved.'));

                return $this->redirect(['controller' => 'UserCards','action' => 'add']);
            }
            $this->Flash->error(__('The user salon could not be saved. Please, try again.'));
        }
        $this->set('userSalon', $userSalon);
        $this->set('_serialize', ['userSalon']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Specialization id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $userSalon = $this->UserSalons->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $userSalon = $this->UserSalons->patchEntity($userSalon, $this->request->getData());
            if ($this->UserSalons->save($userSalon)) {
                $this->Flash->success(__('The User Salon has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The User Salon could not be saved. Please, try again.'));
        }
        $this->set(compact('userSalon'));
        $this->set('_serialize', ['userSalon']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Specialization id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $userSalon = $this->UserSalons->get($id);
        if ($this->UserSalons->delete($userSalon)) {
            $this->Flash->success(__('The user Salon has been deleted.'));
        } else {
            $this->Flash->error(__('The user Salon could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
