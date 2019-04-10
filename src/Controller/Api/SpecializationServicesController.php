<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\Exception;


class SpecializationServicesController extends ApiController

{
    public function initialize(){    
        parent::initialize();
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

        $specializationSevices = $this->SpecializationServices->find()->contain(['Specializations'])->all()->toArray();
        if(!$specializationSevices){
          throw new NotFoundException(__('No entity found in specialization services'));
        }

        $this->set('data',$specializationSevices);
        $this->set('status',true);
        $this->set('_serialize', ['status','data']);
    }

    /**
     * View method
     *
     * @param string|null $id specialization services id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    { 
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $specializationSevice = $this->SpecializationServices->findById($id)->first();
        
        if(!$specializationSevice){
          throw new NotFoundException(__('No entity found in specializationSevice'));
        }

        $this->set('data',$specializationSevice);
        $this->set('status',true);
        $this->set('_serialize', ['status','data']);
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
            
        $specializationSevice = $this->SpecializationServices->newEntity();
        $specializationSevice = $this->SpecializationServices->patchEntity($specializationSevice, $this->request->data);
        
        if (!$this->SpecializationServices->save($specializationSevice)) {
            throw new Exception('This specialization service could not be saved');
        }
        

        $this->set('data',$specializationSevice);
        $this->set('status',true);
        $this->set('_serialize', ['status','data']);
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
        $specializationSevice = $this->SpecializationServices->findById($id)->first();

         if(!$specializationSevice){
          throw new NotFoundException(__('No entity found in specializationSevice'));
        }
        $specializationSevice = $this->SpecializationServices->patchEntity($specializationSevice, $this->request->data);

        if (!$this->SpecializationServices->save($specializationSevice)) {
            throw new Exception('This specialization service could not be saved');
        }
        
        
        $this->set('data',$specializationSevice);
        $this->set('status',true);
        $this->set('_serialize', ['status','data']);
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
        $specializationSevice = $this->SpecializationServices->get($id);
        if(!$specializationSevice){
          throw new NotFoundException(__('No entity found in specializationSevice'));
        }
        if (!$this->SpecializationServices->delete($specializationSevice)) {
            throw new Exception('This specialization service could not be deleted.');
        }

        $this->set('status',true);
        $this->set('_serialize', ['status']);
    }

}
