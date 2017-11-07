<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Core\Exception\Exception;
use Cake\Network\Exception\NotFoundException;

/**
 * ExpertSpecializations Controller
 *
 * @property \App\Model\Table\ExpertSpecializationsTable $ExpertSpecializations
 *
 * @method \App\Model\Entity\ExpertSpecialization[] paginate($object = null, array $settings = [])
 */
class ExpertSpecializationsController extends ApiController
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

        $this->loadModel('Experts');
        $expertId = $this->Experts->findByUserId($this->Auth->user('id'))->first()->id;

        $expertSpecializations = $this->ExpertSpecializations
                                        ->findByExpertId($expertId)
                                        ->contain(['Experts', 'Specializations', 'ExpertSpecializationServices.SpecializationServices'])
                                        ->all();

        $this->set(compact('expertSpecializations'));
        $this->set('_serialize', ['expertSpecializations']);
    }

    public function indexAll()
    {
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        
        $expertSpecializations = $this->ExpertSpecializations
                                        ->find()
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

        $expertSpecialization = $this->ExpertSpecializations
                                    ->findByExpertId($this->request->session()->read('User')['experts'][0]['id'])
                                    ->contain(['Experts', 'Specializations', 'ExpertSpecializationServices'])
                                    ->first();

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
        $data = $this->request->getData();
        $session = $this->request->session();

        $this->loadModel('Experts');
        $expert = $this->Experts->findByUserId($this->Auth->user('id'))
                                ->first();

        $data['expert_id'] = $expert['id'];

        $expertSpecialization = $this->ExpertSpecializations->newEntity();
        $expertSpecialization = $this->ExpertSpecializations->patchEntity($expertSpecialization, $data);

        if (!$this->ExpertSpecializations->save($expertSpecialization)) {
            throw new Exception("Expert specialization could not be saved.");
        }

        $success = true;
        
        $this->set('data',$expertSpecialization);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }


    /**
     * Edit method
     *
     * @param string|null $id Expert Specialization id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($specializationId = null)
    {
        if (!$this->request->is(['patch', 'post', 'put'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        if (!$specializationId) {
            throw new MethodNotAllowedException(__('BAD_REQUEST','Argument Specialization Id missing.'));
        }

        $this->loadModel('Experts');
        $expertId = $this->Experts->findByUserId($this->Auth->user('id'))
                                  ->first()
                                  ->get('id');

        $expertSpecialization = $this->ExpertSpecializations->findBySpecializationId($specializationId)
                                                            ->where(['expert_id' => $expertId])
                                                            ->first();
        
        $expertSpecialization = $this->ExpertSpecializations->patchEntity($expertSpecialization, $this->request->getData());
        if (!$this->ExpertSpecializations->save($expertSpecialization)) {
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
    public function delete($specializationId = null)
    {
        if (!$this->request->is(['patch', 'post', 'put','delete'])) {
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        if (!$specializationId) {
            throw new MethodNotAllowedException(__('BAD_REQUEST','Argument Specialization Id missing.'));
        }

        $this->loadModel('Experts');
        $expertId = $this->Experts->findByUserId($this->Auth->user('id'))
                                  ->first()
                                  ->get('id');
        
        $expertSpecialization = $this->ExpertSpecializations->findBySpecializationId($specializationId)
                                                            ->where(['expert_id' => $expertId])
                                                            ->first();
        
        if (!$this->ExpertSpecializations->delete($expertSpecialization)) {
            throw new Exception("Expert specialization could not be deleted.");
        }

        $success = true;
        
        $this->set('status',$success);
        $this->set('_serialize', ['status']);
    }
}
