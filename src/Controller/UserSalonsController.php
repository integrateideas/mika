<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * UserSalons Controller
 *
 * @property \App\Model\Table\UserSalonsTable $UserSalons
 *
 * @method \App\Model\Entity\UserSalon[] paginate($object = null, array $settings = [])
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
        $this->paginate = [
            'contain' => ['Users']
        ];
        $userSalons = $this->paginate($this->UserSalons);

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
        $userSalon = $this->UserSalons->get($id, [
            'contain' => ['Users', 'Experts']
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
            $userSalon = $this->UserSalons->patchEntity($userSalon, $this->request->getData());
            if ($this->UserSalons->save($userSalon)) {
                $this->Flash->success(__('The user salon has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user salon could not be saved. Please, try again.'));
        }
        $users = $this->UserSalons->Users->find('list', ['limit' => 200]);
        $this->set(compact('userSalon', 'users'));
        $this->set('_serialize', ['userSalon']);
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
        $userSalon = $this->UserSalons->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $userSalon = $this->UserSalons->patchEntity($userSalon, $this->request->getData());
            if ($this->UserSalons->save($userSalon)) {
                $this->Flash->success(__('The user salon has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user salon could not be saved. Please, try again.'));
        }
        $users = $this->UserSalons->Users->find('list', ['limit' => 200]);
        $this->set(compact('userSalon', 'users'));
        $this->set('_serialize', ['userSalon']);
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
        if ($this->UserSalons->delete($userSalon)) {
            $this->Flash->success(__('The user salon has been deleted.'));
        } else {
            $this->Flash->error(__('The user salon could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
