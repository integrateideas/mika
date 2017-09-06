<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\Exception;


class SpecializationsController extends ApiController

{
    public function initialize(){    
        parent::initialize();
        $this->Auth->allow(['index',  'view', 'add', 'edit']);

    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $specializations = $this->Specializations->find()->contain(['SpecializationServices'])->all()->toArray();
        if(!$specializations){
          throw new NotFoundException(__('No entity found in specialization'));
        }

        $this->set(compact('specializations'));
        $this->set('_serialize', ['specializations']);
    }

    /**
     * View method
     *
     * @param string|null $id specialization id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    { 
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $specialization = $this->Specializations->findById($id)->contain(['SpecializationServices'])->first();
        
        if(!$specialization){
          throw new NotFoundException(__('No entity found in specialization'));
        }
        

        $this->set(compact('specialization'));
        $this->set('_serialize', ['specialization']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
            
        $specialization = $this->Specializations->newEntity();
        $specialization = $this->Specializations->patchEntity($specialization, $this->request->data, ['associated' => ['SpecializationServices']]);
       
        if(!$specialization->errors()){
            if ($this->Specializations->save($specialization)) {
                $response['id'] = $specialization->id;
                $response['message'] = 'This specialization has been saved';
           
            } else {
               throw new Exception('This specialization could not be saved');
               $response['message'] = 'This specialization could not be saved';
            }
        }else{
            throw new BadRequestException('This specialization is already exists');
            $response = "Not Created";
        }
        

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    /**
     * Edit method
     *
     * @param string|null $id specialization id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if (!$this->request->is(['patch', 'post', 'put'])) {
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        $specialization = $this->Specializations->findById($id)->first();
        if(!$specialization){
          throw new NotFoundException(__('No entity found in specialization'));
        }
        $specialization = $this->Specializations->patchEntity($specialization, $this->request->data);

        if ($this->Specializations->save($specialization)) {
          $response['message'] = 'This specialization has been saved';
          $response['id']= $specialization->id;
        
        } else {
            throw new Exception('This specialization could not be saved');
        }
        
        
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }
    /**
     * Delete method
     *
     * @param string|null $id specialization id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $specialization = $this->Specializations->get($id);
        if ($this->Specializations->delete($specialization)) {
            $response['message'] = 'This specialization has been deleted successfully';
            $response['id']= $specialization->id;
        } else {
            $response['message'] = 'This specialization could not be deleted';
        }
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

}
