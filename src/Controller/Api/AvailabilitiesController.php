<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\Exception;

/**
 * Availabilities Controller
 *
 * @property \App\Model\Table\AvailabilitiesTable $Availabilities
 *
 * @method \App\Model\Entity\Availability[] paginate($object = null, array $settings = [])
 */
class AvailabilitiesController extends AppController
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

        $availabilities = $this->Availabilities->find()
                                                ->contain(['Experts'])
                                                ->all();

        $this->set(compact('availabilities'));
        $this->set('_serialize', ['availabilities']);
    }

    //TODO: add time constraints
    public function activeAvailabilities()
    {
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $availabilities = $this->Availabilities->find()
                                                ->contain(['Experts'])
                                                ->where(['status' => 1])
                                                ->all();

        $this->set(compact('availabilities'));
        $this->set('_serialize', ['availabilities']);
    }

    /**
     * View method
     *
     * @param string|null $id Availability id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $availability = $this->Availabilities->get($id, [
            'contain' => ['Experts']
        ]);

        $this->set('availability', $availability);
        $this->set('_serialize', ['availability']);
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

        $availability = $this->Availabilities->newEntity();
        
        $availability = $this->Availabilities->patchEntity($availability, $this->request->getData());

        if (!$this->Availabilities->save($availability)) {
            throw new Exception("Availability could not be saved.");
        }
        
        $success = true;

        $this->set(compact('availability', 'success'));
        $this->set('_serialize', ['availability','success']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Availability id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if (!$this->request->is(['patch', 'post', 'put'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $availability = $this->Availabilities->get($id, [
            'contain' => []
        ]);
        
        $availability = $this->Availabilities->patchEntity($availability, $this->request->getData());
        if (!$this->Availabilities->save($availability)) {
            throw new Exception("Availability could not be edited.");
        }
        
        $success = true;
        
        $this->set(compact('availability', 'success'));
        $this->set('_serialize', ['availability','success']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Availability id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if (!$this->request->is(['patch', 'post', 'put','delete'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $availability = $this->Availabilities->get($id);
        
        if (!$this->Availabilities->delete($availability)) {
            throw new Exception("Availability could not be edited.");
        } 
        
        $success = true;
        
        $this->set(compact('success'));
        $this->set('_serialize', ['success']);
    }
}
