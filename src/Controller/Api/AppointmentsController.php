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
use Cake\Collection\Collection;
use App\Controller\AppHelper;
use Cake\I18n\FrozenTime;


/**
 * Appointments Controller
 *
 * @property \App\Model\Table\AppointmentsTable $Appointments
 *
 * @method \App\Model\Entity\Appointment[] paginate($object = null, array $settings = [])
 */
class AppointmentsController extends ApiController
{


    // /**
    //  * View method
    //  *
    //  * @param string|null $id Appointment id.
    //  * @return \Cake\Http\Response|void
    //  * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    //  */
    // public function view($id = null)
    // {
    //     if (!$this->request->is(['get'])) {
    //       throw new MethodNotAllowedException(__('BAD_REQUEST'));
    //     }

    //     $appointment = $this->Appointments->get($id, [
    //         'contain' => ['Users', 'Experts', 'ExpertAvailabilities', 'ExpertSpecializationServices', 'ExpertSpecializations', 'AppointmentTransactions']
    //     ]);

    //     $success = true;

    //     $this->set('data',$appointment);
    //     $this->set('status',$success);
    //     $this->set('_serialize', ['status','data']);
    // }

    // /**
    //  * Add method
    //  *
    //  * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    //  */
    // public function add()
    // {
    //     if(!$this->request->is(['post'])){
    //         throw new MethodNotAllowedException(__('BAD_REQUEST'));
    //     }
    //     $userId = $this->Auth->user('id');        
    //     $this->loadModel('Experts');
    //     $expertId = $this->Experts->findByUserId($userId)
    //                                 ->first()
    //                                 ->get('id');

    //     $data = [
    //                 'user_id' => $userId,
    //                 'expert_id' => $expertId,
    //                 'expert_availability_id' => $this->request->data['expert_availability_id'],
    //                 'expert_specialization_service_id' =>  $this->request->data['expert_specialization_service_id'],
    //                 'expert_specialization_id' =>  $this->request->data['expert_specialization_id']
    //             ];
                
    //     $appointment = $this->Appointments->newEntity();
    //     $appointment = $this->Appointments->patchEntity($appointment, $data);

    //     if (!$this->Appointments->save($appointment)) {
          
    //       if($appointment->errors()){
    //         $this->_sendErrorResponse($appointment->errors());
    //       }
    //       throw new Exception("Error Processing Request");
    //     }
        
    //     $success = true;

    //     $this->set('data',$appointment);
    //     $this->set('status',$success);
    //     $this->set('_serialize', ['status','data']);        
    // }

    // public function getExpertOrderHistory(){
      
    //     if (!$this->request->is(['get'])) {
    //       throw new MethodNotAllowedException(__('BAD_REQUEST'));
    //     }

    //     $userId = $this->Auth->user('id');
    //     //if user is expert or not
    //     $getUserOrderHistory = $this->Appointments->findByUserId($userId)
    //                                               ->contain(['AppointmentTransactions'])
    //                                               ->all();
    //     $success = true;

    //     $this->set('data',$getUserOrderHistory);
    //     $this->set('status',$success);
    //     $this->set('_serialize', ['status','data']);
    // }


    public function confirmBooking($id){

        if(!$this->request->is(['put'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $this->loadModel('Appointments');
        $appointment = $this->Appointments->findById($id)
                                          ->contain(['Users','AppointmentServices.ExpertSpecializationServices.SpecializationServices'])
                                          ->first();
        
        $updateBookingStatus = [
                                    'is_confirmed' => 1
                                ];
        $appointment = $this->Appointments->patchEntity($appointment,$updateBookingStatus);
        if (!$this->Appointments->save($appointment)) {
          
          if($appointment->errors()){
            $this->_sendErrorResponse($appointment->errors());
          }
          throw new Exception("Error Processing Request");
        }
        $this->set('data',$appointment);
        $this->set('status',true);
        $this->set('_serialize', ['status','data']);
    }
    public function yoconfirmBooking($id){

        if(!$this->request->is(['put'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $this->loadModel('Appointments');
        $appointment = $this->Appointments->findById($id)
                                          ->contain(['Users','AppointmentServices.ExpertSpecializationServices.SpecializationServices'])
                                          ->first();

        $expertId = $appointment->expert_id;
        $availabilityId = $appointment->expert_availability_id;                                
        $userName = $appointment->user->first_name;
        $serviceName = (new Collection($appointment->appointment_services))->extract('expert_specialization_service.specialization_service.label')->toArray();
        $servicePrice = (new Collection($appointment->appointment_services))->extract('expert_specialization_service.price')->toArray();
        $userCardId = $appointment['user_card_id'];

        $servicePrice = array_sum($servicePrice);
        $serviceName = implode(', ', $serviceName);
        $this->loadModel('UserCards');
        $userCardDetails = $this->UserCards->findById($userCardId)->first();

        $this->loadComponent('Stripe');
        $userId = $this->Auth->user('id');
        $cardChargeDetails = $this->Stripe->chargeCards($servicePrice,$userCardDetails['stripe_card_id'],$userCardDetails['stripe_customer_id'],$serviceName,$userName);
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
        
        $appointmentId = $updateAppointmentStatus->id;
        $success = true;

        $this->loadModel('ExpertAvailabilities');
        $getAvailabilty = $this->ExpertAvailabilities->findById($availabilityId)->first();
        $updateAvailability = [
                                'status' => 0
                              ];

        $patchAvailabilty = $this->ExpertAvailabilities->patchEntity($getAvailabilty,$updateAvailability);
        if (!$this->ExpertAvailabilities->save($patchAvailabilty)) {
          
          if($patchAvailabilty->errors()){
            $this->_sendErrorResponse($patchAvailabilty->errors());
          }
          throw new Exception("Error Processing Request while Updating the Availability status");
        }

        $this->rejectAll($expertId, $availabilityId,$appointmentId);

        $appHelper = new AppHelper();
        $getNotificationContent = $appHelper->getNotificationText('confirm_booking');
        if(!empty($getNotificationContent)){
            $this->sendNotification($getNotificationContent);
        }
        $this->set('data',$updateAppointmentStatus);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }

    public function sendNotification($getNotificationContent){

        $this->loadComponent('FCMNotification');
        $this->loadModel('Users');
        $deviceToken = $this->Users->UserDeviceTokens->findByUserId($this->Auth->user('id'))->first();

            if($deviceToken){
                $deviceToken = $deviceToken->device_token;
            }else{
                throw new NotFoundException(__('Device token has not been found for this User.'));
            }
        $title = $getNotificationContent['title'];
        $body = $getNotificationContent['body'];
        $data = ['hi' => 'hello'];
        $notification = $this->FCMNotification->sendToExpertApp($title, $body, $deviceToken, $data);
    }

    //Reject all for the slot given in the $appointmentId passed in the params.
    public function rejectAll($expertId, $availabilityId,$appointmentId){
        
        $appointments = $this->Appointments->find()
                                           ->where(['id IS NOT' => $appointmentId, 'expert_id' => $expertId, 'expert_availability_id' => $availabilityId])
                                           ->all()
                                           ->toArray();
        
        foreach ($appointments as $key => $value) {
            
            $value->is_confirmed = 0;
            $appointments = $this->Appointments->save($value);
        }
    }

    public function rejectBooking($id){

        if(!$this->request->is(['get'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $this->loadModel('Appointments');
        $appointment = $this->Appointments->findById($id)
                                          ->first();

        $expertId = $appointment->expert_id;
        $availabilityId = $appointment->expert_availability_id;
        $updateBookingStatus =  [
                                    'is_confirmed' => 0
                                ];

        $appointments = $this->Appointments->patchEntity($appointment,$updateBookingStatus);


        if (!$this->Appointments->save($appointments)) {
          
          throw new Exception("Error Processing Request while rejecting the appointment");
        }

        $success = true;
        $this->rejectAll($expertId, $availabilityId,$appointments->id);

        $appHelper = new AppHelper();
        $getNotificationContent = $appHelper->getNotificationText('reject_booking');
        
        if(!empty($getNotificationContent)){
            $this->sendNotification($getNotificationContent);
        }

        $this->set('data',$appointments);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }

}
