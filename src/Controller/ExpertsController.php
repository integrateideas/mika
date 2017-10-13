<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Experts Controller
 *
 * @property \App\Model\Table\ExpertsTable $Experts
 *
 * @method \App\Model\Entity\Expert[] paginate($object = null, array $settings = [])
 */
class ExpertsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => [ 'UserSalons','Users']
        ];
        $experts = $this->paginate($this->Experts);
        // $experts = $this->Experts->find()->contain(['Users' => function($q){

        //     }])->all();
        // pr($experts);die;

        $this->set(compact('experts'));
        $this->set('_serialize', ['experts']);
    }

    /**
     * View method
     *
     * @param string|null $id Expert id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $expert = $this->Experts->get($id, [
            'contain' => ['Users', 'UserSalons', 'ExpertAvailabilities', 'ExpertCards', 'ExpertLocations', 'ExpertSpecializationServices', 'ExpertSpecializations']
        ]);

        $this->set('expert', $expert);
        $this->set('_serialize', ['expert']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $expert = $this->Experts->newEntity();
        if ($this->request->is('post')) {
            $expert = $this->Experts->patchEntity($expert, $this->request->getData());
            if ($this->Experts->save($expert)) {
                $this->Flash->success(__('The expert has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The expert could not be saved. Please, try again.'));
        }
        $users = $this->Experts->Users->find('list', ['limit' => 200]);
        $userSalons = $this->Experts->UserSalons->find('list', ['limit' => 200]);
        $this->set(compact('expert', 'users', 'userSalons'));
        $this->set('_serialize', ['expert']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Expert id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $expert = $this->Experts->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $expert = $this->Experts->patchEntity($expert, $this->request->getData());
            if ($this->Experts->save($expert)) {
                $this->Flash->success(__('The expert has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The expert could not be saved. Please, try again.'));
        }
        $users = $this->Experts->Users->find('list', ['limit' => 200]);
        $userSalons = $this->Experts->UserSalons->find('list', ['limit' => 200]);
        $this->set(compact('expert', 'users', 'userSalons'));
        $this->set('_serialize', ['expert']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Expert id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $expert = $this->Experts->get($id);
        if ($this->Experts->delete($expert)) {
            $this->Flash->success(__('The expert has been deleted.'));
        } else {
            $this->Flash->error(__('The expert could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
