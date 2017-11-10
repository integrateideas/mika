<?php
namespace App\Controller\Api\User;

use App\Controller\Api\User\ApiController;
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
use Cake\Collection\Collection;

/**
 * UserFavouriteExperts Controller
 *
 * @property \App\Model\Table\UserFavouriteExpertsTable $UserFavouriteExperts
 *
 * @method \App\Model\Entity\UserFavouriteExpert[] paginate($object = null, array $settings = [])
 */
class AppointmentBookingsController extends ApiController
{

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        //api for booking an appointment
        if(!$this->request->is(['post'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        
        $data = $this->request->data;
        $data = [
                    'user_id' => $this->Auth->user('id'),
                    'expert_id' => $this->request->data['expert_id'],
                    'expert_availability_id' => $this->request->data['expert_availability_id'],
                    'expert_specialization_id' => $this->request->data['expert_specialization_id'],
                    'expert_specialization_service_id' => $this->request->data['expert_specialization_service_id'],
                    'expert_specialization_service_id' => $this->request->data['expert_specialization_service_id'],
                    'user_card_id' => $this->request->data['user_card_id']
                ];

        $this->loadModel('Appointments');
        $bookingAppointment = $this->Appointments->newEntity();
        $bookingAppointment = $this->Appointments->patchEntity($bookingAppointment, $data);

        if (!$this->Appointments->save($bookingAppointment)) {
          
          if($bookingAppointment->errors()){
            $this->_sendErrorResponse($bookingAppointment->errors());
          }
          throw new Exception("Error Processing Request");
        }
        
        $success = true;
        
        $this->set('data',$bookingAppointment);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }

    public function confirmBooking(){

        $data = $this->request->data;

        if(!isset($data['appointment_id']) || !$data['appointment_id']){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        $this->loadModel('Appointments');
        $appointment = $this->Appointments->findById($data['appointment_id'])->first();
        $userCardId = $appointment['user_card_id'];
        
        $this->loadModel('UserCards');
        $userCardDetails = $this->UserCards->findById($userCardId)->first();
        
        $this->loadComponent('Stripe');
        $userId = $this->Auth->user('id');
        $cardChargeDetails = $this->Stripe->chargeCards($userId,$userCardDetails['stripe_card_id'],$userCardDetails['stripe_customer_id']);
        
        $reqData = [
                        'transaction_amount' => $cardChargeDetails['data']['amount'],
                        'stripe_charge_id' => $cardChargeDetails['data']['id'],
                        'status' => $cardChargeDetails['status'],
                        'remark' => $cardChargeDetails['data']['description']? $cardChargeDetails['data']['description'] : null,
                        'user_card_id' => $userCardDetails->id
                    ];

        $this->loadModel('Transactions');
        $transaction = $this->Transactions->newEntity();
        $transaction = $this->Transactions->patchEntity($transaction,$reqData);
        if (!$this->Transactions->save($transaction)) {
          
          if($transaction->errors()){
            $this->_sendErrorResponse($transaction->errors());
          }
          throw new Exception("Error Processing Request");
        }
        $updateBookingStatus = [
                                    'is_confirmed' => 1,
                                    'transaction_id' => $transaction->id
                                ];
        $updateAppointmentStatus = $this->Appointments->patchEntity($appointment,$updateBookingStatus);
        if (!$this->Appointments->save($updateAppointmentStatus)) {
          
          if($updateAppointmentStatus->errors()){
            $this->_sendErrorResponse($updateAppointmentStatus->errors());
          }
          throw new Exception("Error Processing Request");
        }
        $success = true;

        $this->set('data',$updateAppointmentStatus);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }


}
