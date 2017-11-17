<?php
namespace App\Controller\Api\User;

use App\Controller\Api\User\ApiController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Core\Exception\Exception;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\UnauthorizedException;
// use Cake\Auth\DefaultPasswordHasher;
// use Firebase\JWT\JWT;
// use Cake\Utility\Security;
// use Cake\I18n\Time;
// use Cake\Core\Configure;
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
        
        $userId = $this->Auth->user('id');
      
        if(!$userId){
            throw new NotFoundException(__('We cant identify the user.'));
        }

        $data = $this->request->getData();

        if(!isset($data['stripeCardId']) || !$data['stripeCardId']){
            throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING',"Stripe card id"));
        }
        if(!isset($data['expertId']) || !$data['expertId']){
            throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING',"Expert id"));
        }

        if(!isset($data['availabilityId']) || !$data['availabilityId']){
            throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING',"Expert Availability id"));

        }
        if(!isset($data['expSpecServiceId']) || !$data['expSpecServiceId']){
            throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING',"Expert Specialization Service id"));
        }
        $this->loadModel('ExpertSpecializationServices');
        $expertSpecializationId = $this->ExpertSpecializationServices->findById($data['expSpecServiceId'])->first()->expert_specialization_id;

        if(!$expertSpecializationId){
            throw new NotFoundException(__('Expert Specialization id not found.'));
        }

        $this->loadModel('UserCards');
        $getCardDetails = $this->UserCards->findByUserId($userId)
                                    ->where(['stripe_card_id' => $data['stripeCardId']])
                                    ->first();

        if(!$getCardDetails){
            throw new NotFoundException(__('User Card details not found.'));
        }
        $userCardId = $getCardDetails->id;

        $data = [
                    'user_id' => $this->Auth->user('id'),
                    'expert_id' => $data['expertId'],
                    'expert_availability_id' => $data['availabilityId'],
                    'expert_specialization_id' => $expertSpecializationId,
                    'expert_specialization_service_id' => $data['expSpecServiceId'],
                    'user_card_id' => $userCardId
                ];
        $this->loadModel('Appointments');
        $bookingAppointment = $this->Appointments->newEntity();
        $bookingAppointment = $this->Appointments->patchEntity($bookingAppointment, $data);

        if (!$this->Appointments->save($bookingAppointment,['user_id' =>$userId])) {
          
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

    public function confirmBooking($id){

        if(!$this->request->is(['get'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        if(!isset($id) || !$id){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        $this->loadModel('Appointments');
        $appointment = $this->Appointments->findById($id)
                                          ->contain(['Users','ExpertSpecializationServices.SpecializationServices'])
                                          ->first();

        $expertId = $appointment->expert_id;
        $availabilityId = $appointment->expert_availability_id;                                
        $userName = $appointment->user->first_name;
        
        $servicePrice = $appointment->expert_specialization_service->price;
        $serviceName = $appointment->expert_specialization_service->specialization_service->label;
        
        $userCardId = $appointment['user_card_id'];

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
        $this->rejectAppointments($expertId, $availabilityId,$appointmentId);
        $this->set('data',$updateAppointmentStatus);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }

    public function rejectAppointments($expertId, $availabilityId,$appointmentId){
        
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
        $appointment->is_confirmed = 0;
        $updateBookingStatus =  [
                                    'is_confirmed' => 0
                                ];

        $appointments = $this->Appointments->patchEntity($appointment,$updateBookingStatus);
        
        $success = true;
        $this->rejectAppointments($expertId, $availabilityId,$appointments->id);
        $this->set('data',$appointments);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }

    // List of Appointmnets on the basis of User or Expert and Filters
    public function index(){
        

        if(!$this->request->is(['get'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $userId = $this->Auth->user('id');
        //check weather this user is an expert 
        $this->loadModel('Experts');
        $expert = $this->Experts->findByUserId($userId)->first();

        $this->loadModel('Appointments');
        
        if($expert){
            $reqData = $this->Appointments->findByExpertId($expert->id);
        }else{
            $reqData = $this->Appointments->findByUserId($userId);
        }
        
        $reqData = $reqData->contain(['ExpertSpecializationServices.SpecializationServices','ExpertSpecializations.Specializations','Transactions', 'ExpertAvailabilities', 'Experts.Users']);

        $filter = $this->request->query('filter');
        if($filter){

            switch ($filter) {
                case 'all':
                    $where = [''];
                    break;
                case 'pending':
                    $where = ['is_confirmed IS NULL','is_completed IS NULL'];
                    break;
                case 'rejected':
                    $where = ['is_confirmed' => 0,'is_completed IS NULL'];
                    break;
                case 'confirmed':
                    $where = ['is_confirmed' => 1,'is_completed IS NULL'];
                    break;
                case 'cancelled':
                    $where = ['is_confirmed' => 1,'is_completed' => 0];
                    break;
                case 'completed':
                    $where = ['is_confirmed' => 1,'is_completed' => 1];
                    break;
                
            }
        }

        $reqData = $reqData->where($where)->all()->toArray();

        $success = true;

        $this->set('data',$reqData);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }


}
