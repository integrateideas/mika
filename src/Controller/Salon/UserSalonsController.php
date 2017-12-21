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
        $userSalon = $this->paginate($this->UserSalons);

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
        $specialization = $this->Specializations->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $specialization = $this->Specializations->patchEntity($specialization, $this->request->getData());
            if ($this->Specializations->save($specialization)) {
                $this->Flash->success(__('The specialization has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The specialization could not be saved. Please, try again.'));
        }
        $this->set(compact('specialization'));
        $this->set('_serialize', ['specialization']);
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
        $specialization = $this->Specializations->get($id);
        if ($this->Specializations->delete($specialization)) {
            $this->Flash->success(__('The specialization has been deleted.'));
        } else {
            $this->Flash->error(__('The specialization could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
