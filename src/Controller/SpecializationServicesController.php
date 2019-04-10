<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SpecializationServices Controller
 *
 * @property \App\Model\Table\SpecializationServicesTable $SpecializationServices
 *
 * @method \App\Model\Entity\SpecializationService[] paginate($object = null, array $settings = [])
 */
class SpecializationServicesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Specializations']
        ];
        $specializationServices = $this->paginate($this->SpecializationServices);

        $this->set(compact('specializationServices'));
        $this->set('_serialize', ['specializationServices']);
    }

    /**
     * View method
     *
     * @param string|null $id Specialization Service id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $specializationService = $this->SpecializationServices->get($id, [
            'contain' => ['Specializations', 'ExpertSpecializationServices']
        ]);

        $this->set('specializationService', $specializationService);
        $this->set('_serialize', ['specializationService']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $specializationService = $this->SpecializationServices->newEntity();
        if ($this->request->is('post')) {
            $specializationService = $this->SpecializationServices->patchEntity($specializationService, $this->request->getData());
            if ($this->SpecializationServices->save($specializationService)) {
                $this->Flash->success(__('The specialization service has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The specialization service could not be saved. Please, try again.'));
        }
        $specializations = $this->SpecializationServices->Specializations->find('list', ['limit' => 200]);
        $this->set(compact('specializationService', 'specializations'));
        $this->set('_serialize', ['specializationService']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Specialization Service id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $specializationService = $this->SpecializationServices->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $specializationService = $this->SpecializationServices->patchEntity($specializationService, $this->request->getData());
            if ($this->SpecializationServices->save($specializationService)) {
                $this->Flash->success(__('The specialization service has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The specialization service could not be saved. Please, try again.'));
        }
        $specializations = $this->SpecializationServices->Specializations->find('list', ['limit' => 200]);
        $this->set(compact('specializationService', 'specializations'));
        $this->set('_serialize', ['specializationService']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Specialization Service id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $specializationService = $this->SpecializationServices->get($id);
        if ($this->SpecializationServices->delete($specializationService)) {
            $this->Flash->success(__('The specialization service has been deleted.'));
        } else {
            $this->Flash->error(__('The specialization service could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
