<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;
use App\Controller\AppHelper;
use Cake\Collection\Collection;
use Cake\Datasource\ModelAwareTrait;
use Cake\Controller\Controller;
use Cake\Controller\ComponentRegistry;
/**
 * Appointments Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ExpertsTable|\Cake\ORM\Association\BelongsTo $Experts
 * @property \App\Model\Table\ExpertAvailabilitiesTable|\Cake\ORM\Association\BelongsTo $ExpertAvailabilities
 * @property \App\Model\Table\ExpertSpecializationServicesTable|\Cake\ORM\Association\BelongsTo $ExpertSpecializationServices
 * @property \App\Model\Table\ExpertSpecializationsTable|\Cake\ORM\Association\BelongsTo $ExpertSpecializations
 * @property |\Cake\ORM\Association\BelongsTo $Transactions
 * @property |\Cake\ORM\Association\BelongsTo $UserCards
 *
 * @method \App\Model\Entity\Appointment get($primaryKey, $options = [])
 * @method \App\Model\Entity\Appointment newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Appointment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Appointment|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Appointment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Appointment[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Appointment findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AppointmentsTable extends Table
{
use ModelAwareTrait;
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('appointments');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Experts', [
            'foreignKey' => 'expert_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ExpertAvailabilities', [
            'foreignKey' => 'expert_availability_id',
            'joinType' => 'LEFT'
        ]);
        // $this->belongsTo('ExpertSpecializationServices', [
        //     'foreignKey' => 'expert_specialization_service_id',
        //     'joinType' => 'INNER'
        // ]);
        // $this->belongsTo('ExpertSpecializations', [
        //     'foreignKey' => 'expert_specialization_id',
        //     'joinType' => 'INNER'
        // ]);
        $this->belongsTo('Transactions', [
            'foreignKey' => 'transaction_id'
        ]);
        $this->belongsTo('UserCards', [
            'foreignKey' => 'user_card_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('AppointmentServices', [
            'foreignKey' => 'appointment_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->boolean('is_confirmed')
            ->allowEmpty('is_confirmed');

        $validator
            ->boolean('is_completed')
            ->allowEmpty('is_completed');

        return $validator;
    }

     public function beforeSave($event,$entity, $options)
    {   
        if($entity->is_confirmed == 1){
             $expertId = $entity->expert_id;
             $availabilityId = $entity->expert_availability_id;                                
             $userName = $entity->user->first_name;
             $serviceName = (new Collection($entity->appointment_services))->extract('expert_specialization_service.specialization_service.label')->toArray();
            $servicePrice = (new Collection($entity->appointment_services))->extract('expert_specialization_service.price')->toArray();
            $userCardId = $entity['user_card_id'];

            $servicePrice = array_sum($servicePrice);
            $serviceName = implode(', ', $serviceName);
             $controller = new Controller();
            $stripe = $controller->loadComponent('Stripe');
        
            $this->loadModel('UserCards');
            $userCardDetails = $this->UserCards->findById($userCardId)->first();

            $cardChargeDetails = $stripe->chargeCards($servicePrice,$userCardDetails['stripe_card_id'],$userCardDetails['stripe_customer_id'],$serviceName,$userName);
                
          $reqData = [
                        'transaction_amount' => ($cardChargeDetails['data']['amount'])/100,
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
        
        $entity->transaction_id = $transaction->id;
    }
}


    public function afterSave($event,$entity, $options)
    {   
        $appointmentData = $this->findById($entity->id)->contain(['ExpertAvailabilities','AppointmentServices.ExpertSpecializations.Specializations','Users','Experts.Users'])->first();
        $services = (new Collection($appointmentData->appointment_services))->extract('expert_specialization.specialization.label')->toArray();
        $services = implode(', ', $services);
        if($entity->is_confirmed === null){
            $data = [
                        'block_identifier' => "appointment_booking_request",
                        'user_id' => $appointmentData->user->id,
                        'custName' => $appointmentData->user->first_name.' '.(($appointmentData->user->last_name)?$appointmentData->user->last_name:''),
                        'status' => 0,
                        'appointmentId' => $appointmentData->id,
                        'reqTime'=>$appointmentData->expert_availability->available_from,
                        'expertName' => $appointmentData->expert->user->first_name.' '.(($appointmentData->expert->user->last_name)?$appointmentData->expert->user->last_name:''),
                        'expertId' => $appointmentData->expert_id,
                        'serviceName' => $services
                    ];
            $appHelper = new AppHelper();
            $updateConversation = $appHelper->createSingleConversation($data);  
            
        }elseif($entity->is_confirmed){
            $this->loadModel('ExpertAvailabilities');

            $this->ExpertAvailabilities->updateAll(['status'=>0],['id'=>$entity->expert_availability_id]);

        $this->rejectAll($appointmentData->expert_id, $entity->expert_availability_id,$appointmentData->id);

        $appHelper = new AppHelper();
        $getNotificationContent = $appHelper->getNotificationText('confirm_booking');
        if(!empty($getNotificationContent)){
            $this->sendNotification($getNotificationContent,$entity->user_id, $entity);
        }
        }
    }

    public function rejectAll($expertId, $availabilityId,$appointmentId){
        $this->updateAll(['is_confirmed'=>0],['id IS NOT' => $appointmentId, 'expert_id' => $expertId, 'expert_availability_id' => $availabilityId]);
    }

     public function sendNotification($getNotificationContent,$userId,$entity = false){

         $controller = new Controller();
            $this->FCMNotification = $controller->loadComponent('FCMNotification');
//hriday
        $appointment = $this->findById($entity->id)
                                          ->contain(['Users','AppointmentServices.ExpertSpecializationServices.SpecializationServices','Experts.Users'])->first();
    
        $this->loadModel('Users');
        $deviceTokens = $this->Users->UserDeviceTokens->findByUserId($userId)
                                                    ->all()
                                                    ->extract('device_token')
                                                    ->toArray();
        if($deviceTokens){

            $title = $getNotificationContent['title'];
            $body = $getNotificationContent['body'];
            $data = ['notificationType' => 'booking response', 'appointment' => $appointment];

            $notification[] = $this->FCMNotification->sendToUserApp($title, $body, $deviceTokens, $data);
        }else{
            throw new NotFoundException(__('Device token has not been found for this User.'));
        }
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['expert_id'], 'Experts'));
        $rules->add($rules->existsIn(['expert_availability_id'], 'ExpertAvailabilities'));
        // $rules->add($rules->existsIn(['expert_specialization_service_id'], 'ExpertSpecializationServices'));
        // $rules->add($rules->existsIn(['expert_specialization_id'], 'ExpertSpecializations'));
        $rules->add($rules->existsIn(['transaction_id'], 'Transactions'));
        $rules->add($rules->existsIn(['user_card_id'], 'UserCards'));

        return $rules;
    }
}
