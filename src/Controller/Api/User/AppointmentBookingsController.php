<?php
namespace App\Controller\Api\User;

use App\Controller\Api\User\ApiController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\NotAllowedException;
use Cake\Core\Exception\Exception;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Log\Log;
use Cake\Collection\Collection;
use App\Controller\AppHelper;

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

        $this->loadModel('Experts');
        $checkUnAuthorizedUser = $this->Experts->findById($data['expertId'])->first();
        if($this->Auth->user('id') == $checkUnAuthorizedUser['user_id']){
            throw new UnauthorizedException(__('You are not allowed to book yourself.'));
        }
        $this->loadModel('ExpertSpecializationServices');
        $expertSpecializationIds = $this->ExpertSpecializationServices->find()
                                                                      ->where(['id IN' => $data['expSpecServiceIds']])
                                                                      ->all()
                                                                      ->combine('id','expert_specialization_id')
                                                                      ->toArray();

        
        if(!$expertSpecializationIds){
            throw new NotFoundException(__('Expert Specialization ids not found.'));
        }
        
        $this->loadModel('UserCards');
        $getCardDetails = $this->UserCards->findByUserId($userId)
                                          ->where(['stripe_card_id' => $data['stripeCardId']])
                                          ->first();

        if(!$getCardDetails){
            throw new NotFoundException(__('User Card details not found.'));
        }
        $userCardId = $getCardDetails->id;

        $reqData = [
                        'user_id' => $this->Auth->user('id'),
                        'expert_id' => $data['expertId'],
                        'expert_availability_id' => $data['availabilityId'],
                        'user_card_id' => $userCardId
                    ];
        $services = [];
        foreach ($data['expSpecServiceIds'] as $key => $value) {
            $reqData['appointment_services'][] = [
                                                    'expert_specialization_id' => $expertSpecializationIds[$value],
                                                    'expert_specialization_service_id' => $value,
                                                    'status' => 1
                                                  ];
        }
        
        $this->loadModel('Appointments');
        $bookingAppointment = $this->Appointments->newEntity();
        $bookingAppointment = $this->Appointments->patchEntity($bookingAppointment, $reqData,['associated' => 'AppointmentServices']);
        
        $expertsUserId = $this->Appointments->Experts->findById($reqData['expert_id'])->first()->user_id;
        Log::write('debug',$data); 
        if (!$this->Appointments->save($bookingAppointment,['user_id' =>$expertsUserId])) { 

          if($bookingAppointment->errors()){
            $this->_sendErrorResponse($bookingAppointment->errors());
          }
          throw new Exception("Error Processing Request");
        }        
        $success = true;
        $appHelper = new AppHelper();
        $getNotificationContent = $appHelper->getNotificationText('appointment_booking');
        
        if(!empty($getNotificationContent)){

            $this->loadComponent('FCMNotification');
            $this->loadModel('Experts');
            $deviceTokens = $this->Experts->findById($bookingAppointment['expert_id'])
                                            ->contain(['Users.UserDeviceTokens'])
                                            ->extract('user.user_device_tokens.{*}.device_token')
                                            ->toArray();
            
            if($deviceTokens){
                $title = $getNotificationContent['title'];
                $body = $getNotificationContent['body'];

                $appointment = $this->Appointments->findById($bookingAppointment['id'])
                                                    ->contain(['AppointmentServices.ExpertSpecializations.Specializations','AppointmentServices.ExpertSpecializationServices.SpecializationServices'])
                                                    ->first();
                $data = ['notificationType' => 'booking request','appointment' => $appointment];
                
                $notification = $this->FCMNotification->sendToExpertApp($title, $body, $deviceTokens, $data);
            }else{
                throw new NotFoundException(__('Device token has not been found for this User.'));
            }

        
        }else{
            throw new Exception("Error Processing Request. Notification Content is not available.");
        } 

        $this->set('data',$bookingAppointment);
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
        $reqData = $reqData->contain([  'Users',
                                        'AppointmentServices.ExpertSpecializationServices.SpecializationServices',
                                        'AppointmentServices.ExpertSpecializations.Specializations',
                                        'Transactions', 
                                        'ExpertAvailabilities', 
                                        'Experts.Users',
                                        'AppointmentReviews']);

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
        if(empty($reqData)){
            throw new NotFoundException(__('No Appointment found for this user.'));
        }

        $success = true;

        $this->set('data',$reqData);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }

    public function view($id){
        
        if(!$this->request->is(['get'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $userId = $this->Auth->user('id');
        //check weather this user is an expert 
        $this->loadModel('Experts');
        $expert = $this->Experts->findByUserId($userId)->first();
        $this->loadModel('Appointments');
        
        $reqData = $this->Appointments->findById($id);

        if($expert){
            $reqData = $reqData->where(['expert_id' => $expert->id]);
        }else{
            $reqData = $reqData->where(['Appointments.user_id' => $userId]);
        }

        $reqData = $reqData ->contain([  'Users',
                                        'AppointmentServices.ExpertSpecializationServices.SpecializationServices',
                                        'AppointmentServices.ExpertSpecializations.Specializations',
                                        'Transactions', 
                                        'ExpertAvailabilities', 
                                        'Experts.Users',
                                        'AppointmentReviews'])
                            ->first();

        if(empty($reqData)){
            throw new NotFoundException(__('No Appointment found for this user.'));
        }

        $success = true;

        $this->set('data',$reqData);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }

}
