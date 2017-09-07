<?php
namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * ExpertLocations Controller
 *
 * @property \App\Model\Table\ExpertLocationsTable $ExpertLocations
 *
 * @method \App\Model\Entity\ExpertLocation[] paginate($object = null, array $settings = [])
 */
class ExpertLocationsController extends AppController
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
        $expertLocations = $this->ExpertLocations->find()
                                                ->contain(['Experts'])
                                                ->all();

        $this->set(compact('expertLocations'));
        $this->set('_serialize', ['expertLocations']);
    }

    /**
     * View method
     *
     * @param string|null $id Expert Location id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {   
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $expertLocation = $this->ExpertLocations->get($id, [
            'contain' => ['Experts']
        ]);

        $this->set('expertLocation', $expertLocation);
        $this->set('_serialize', ['expertLocation']);
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

        $expertLocation = $this->ExpertLocations->newEntity();
        
        $expertLocation = $this->ExpertLocations->patchEntity($expertLocation, $this->request->getData());

        if (!$this->ExpertLocations->save($expertLocation)) {
            throw new Exception("Expert location could not be saved.");
        }
        
        $success = true;

        $this->set(compact('expertLocation', 'success'));
        $this->set('_serialize', ['expertLocation','success']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Expert Location id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if (!$this->request->is(['patch', 'post', 'put'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $expertLocation = $this->ExpertLocations->get($id, [
            'contain' => []
        ]);
        
        $expertLocation = $this->ExpertLocations->patchEntity($expertLocation, $this->request->getData());

        if (!$this->ExpertLocations->save($expertLocation)) {
            throw new Exception("Expert location edits could not be saved.");
        }
        
        $success = true;
        $this->set(compact('expertLocation', 'success'));
        $this->set('_serialize', ['expertLocation','success']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Expert Location id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if (!$this->request->is(['patch', 'post', 'put','delete'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $expertLocation = $this->ExpertLocations->get($id);
        
        if (!($this->ExpertLocations->delete($expertLocation))) {
            throw new Exception("Expert location could not be deleted.");
        }

        $success = true;
        
        $this->set(compact('success'));
        $this->set('_serialize', ['success']);
    }
}
