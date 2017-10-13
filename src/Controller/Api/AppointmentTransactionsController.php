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
use Cake\Log\Log;

/**
 * AppointmentTransactions Controller
 *
 * @property \App\Model\Table\AppointmentTransactionsTable $AppointmentTransactions
 *
 * @method \App\Model\Entity\AppointmentTransaction[] paginate($object = null, array $settings = [])
 */
class AppointmentTransactionsController extends ApiController
{

    /**
     * View method
     *
     * @param string|null $id Appointment Transaction id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $appointmentTransaction = $this->AppointmentTransactions->get($id, [
            'contain' => ['Appointments']
        ]);

        $this->set('appointmentTransaction', $appointmentTransaction);
        $this->set('_serialize', ['appointmentTransaction']);
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

        $data = [
                    'appointment_id' => $this->request->data['appointment_id'],
                    'transaction_amount' => $this->request->data['transaction_amount'],
                    'charge_id' => $this->request->data['charge_id'],
                    'status' =>  1,
                    'remark' =>  $this->request->data['remark']
                ];
                
        $appointmentTransaction = $this->AppointmentTransactions->newEntity();
        $appointmentTransaction = $this->AppointmentTransactions->patchEntity($appointmentTransaction, $data);

        if (!$this->AppointmentTransactions->save($appointmentTransaction)) {
          
          if($appointmentTransaction->errors()){
            $this->_sendErrorResponse($appointmentTransaction->errors());
          }
          throw new Exception("Error Processing Request");
        }

        $this->set(compact('appointmentTransaction', 'appointments', 'charges'));
        $this->set('_serialize', ['appointmentTransaction']);
    }
}
