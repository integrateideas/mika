<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ExpertAvailabilities Controller
 *
 * @property \App\Model\Table\ExpertAvailabilitiesTable $ExpertAvailabilities
 *
 * @method \App\Model\Entity\ExpertAvailability[] paginate($object = null, array $settings = [])
 */
class ExpertAvailabilitiesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Experts']
        ];
        $expertAvailabilities = $this->paginate($this->ExpertAvailabilities);

        $this->set(compact('expertAvailabilities'));
        $this->set('_serialize', ['expertAvailabilities']);
    }

    /**
     * View method
     *
     * @param string|null $id Expert Availability id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $expertAvailability = $this->ExpertAvailabilities->get($id, [
            'contain' => ['Experts']
        ]);

        $this->set('expertAvailability', $expertAvailability);
        $this->set('_serialize', ['expertAvailability']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $expertAvailability = $this->ExpertAvailabilities->newEntity();
        if ($this->request->is('post')) {
            $expertAvailability = $this->ExpertAvailabilities->patchEntity($expertAvailability, $this->request->getData());
            if ($this->ExpertAvailabilities->save($expertAvailability)) {
                $this->Flash->success(__('The expert availability has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The expert availability could not be saved. Please, try again.'));
        }
        $experts = $this->ExpertAvailabilities->Experts->find('list', ['limit' => 200]);
        $this->set(compact('expertAvailability', 'experts'));
        $this->set('_serialize', ['expertAvailability']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Expert Availability id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $expertAvailability = $this->ExpertAvailabilities->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $expertAvailability = $this->ExpertAvailabilities->patchEntity($expertAvailability, $this->request->getData());
            if ($this->ExpertAvailabilities->save($expertAvailability)) {
                $this->Flash->success(__('The expert availability has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The expert availability could not be saved. Please, try again.'));
        }
        $experts = $this->ExpertAvailabilities->Experts->find('list', ['limit' => 200]);
        $this->set(compact('expertAvailability', 'experts'));
        $this->set('_serialize', ['expertAvailability']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Expert Availability id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $expertAvailability = $this->ExpertAvailabilities->get($id);
        if ($this->ExpertAvailabilities->delete($expertAvailability)) {
            $this->Flash->success(__('The expert availability has been deleted.'));
        } else {
            $this->Flash->error(__('The expert availability could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
