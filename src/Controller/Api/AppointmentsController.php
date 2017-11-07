<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Core\Exception\Exception;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Auth\DefaultPasswordHasher;
use Firebase\JWT\JWT;
use Cake\Utility\Security;
use Cake\I18n\Time;
use Cake\Core\Configure;
use Cake\Log\Log;;

/**
 * Appointments Controller
 *
 * @property \App\Model\Table\AppointmentsTable $Appointments
 *
 * @method \App\Model\Entity\Appointment[] paginate($object = null, array $settings = [])
 */
class AppointmentsController extends ApiController
{


    /**
     * View method
     *
     * @param string|null $id Appointment id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $appointment = $this->Appointments->get($id, [
            'contain' => ['Users', 'Experts', 'ExpertAvailabilities', 'ExpertSpecializationServices', 'ExpertSpecializations', 'AppointmentTransactions']
        ]);

        $success = true;

        $this->set('data',$appointment);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if(!$this->request->is(['post'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        $userId = $this->Auth->user('id');        
        $this->loadModel('Experts');
        $expertId = $this->Experts->findByUserId($userId)
                                    ->first()
                                    ->get('id');

        $data = [
                    'user_id' => $userId,
                    'expert_id' => $expertId,
                    'expert_availability_id' => $this->request->data['expert_availability_id'],
                    'expert_specialization_service_id' =>  $this->request->data['expert_specialization_service_id'],
                    'expert_specialization_id' =>  $this->request->data['expert_specialization_id']
                ];
                
        $appointment = $this->Appointments->newEntity();
        $appointment = $this->Appointments->patchEntity($appointment, $data);

        if (!$this->Appointments->save($appointment)) {
          
          if($appointment->errors()){
            $this->_sendErrorResponse($appointment->errors());
          }
          throw new Exception("Error Processing Request");
        }
        
        $success = true;

        $this->set('data',$appointment);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);        
    }



    public function getExpertOrderHistory(){
      
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $userId = $this->Auth->user('id');
        //if user is expert or not
        $getUserOrderHistory = $this->Appointments->findByUserId($userId)
                                                  ->contain(['AppointmentTransactions'])
                                                  ->all();
        $success = true;

        $this->set('data',$getUserOrderHistory);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }
}
