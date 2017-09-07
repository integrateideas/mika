<?php
namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * ExpertSpecializations Controller
 *
 * @property \App\Model\Table\ExpertSpecializationsTable $ExpertSpecializations
 *
 * @method \App\Model\Entity\ExpertSpecialization[] paginate($object = null, array $settings = [])
 */
class ExpertSpecializationsController extends AppController
{

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

        $expertSpecializations = $this->ExpertSpecializations->find()
                                                            ->contain(['Experts', 'Specializations'])
                                                            ->all();

        $this->set(compact('expertSpecializations'));
        $this->set('_serialize', ['expertSpecializations']);
    }

    /**
     * View method
     *
     * @param string|null $id Expert Specialization id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $expertSpecialization = $this->ExpertSpecializations->get($id, [
            'contain' => ['Experts', 'Specializations', 'ExpertSpecializationServices']
        ]);

        $this->set('expertSpecialization', $expertSpecialization);
        $this->set('_serialize', ['expertSpecialization']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if (!$this->request->is(['post'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $expertSpecialization = $this->ExpertSpecializations->newEntity();
        $expertSpecialization = $this->ExpertSpecializations->patchEntity($expertSpecialization, $this->request->getData());

        if (!$this->ExpertSpecializations->save($expertSpecialization)) {
            throw new Exception("Expert specialization could not be saved.");
        }

        $success = true;

        $this->set(compact('expertSpecialization', 'success'));
        $this->set('_serialize', ['expertSpecialization', 'success']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Expert Specialization id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if (!$this->request->is(['patch', 'post', 'put'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $expertSpecialization = $this->ExpertSpecializations->get($id, [
            'contain' => []
        ]);
        
        $expertSpecialization = $this->ExpertSpecializations->patchEntity($expertSpecialization, $this->request->getData());
        if ($this->ExpertSpecializations->save($expertSpecialization)) {
            throw new Exception("Expert specialization edits could not be saved.");
        }

        $this->set(compact('expertSpecialization', 'experts', 'specializations'));
        $this->set('_serialize', ['expertSpecialization']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Expert Specialization id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if (!$this->request->is(['patch', 'post', 'put','delete'])) {
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        
        $expertSpecialization = $this->ExpertSpecializations->get($id);
        
        if (!$this->ExpertSpecializations->delete($expertSpecialization)) {
            throw new Exception("Expert specialization could not be deleted.");
        }

        $success = true;
        
        $this->set(compact('success'));
        $this->set('_serialize', ['success']);
    }
}
