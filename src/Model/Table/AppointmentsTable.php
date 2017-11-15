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
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ExpertSpecializationServices', [
            'foreignKey' => 'expert_specialization_service_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ExpertSpecializations', [
            'foreignKey' => 'expert_specialization_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Transactions', [
            'foreignKey' => 'transaction_id'
        ]);
        $this->belongsTo('UserCards', [
            'foreignKey' => 'user_card_id',
            'joinType' => 'INNER'
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

    public function afterSave($event,$entity,$options)
    {   
        Log::write('debug',$entity);
        $userId = $options->offsetGet('user_id');
        if(!$userId){
            throw new NotFoundException(__('User id not found.'));
        }
        if(!$entity->is_confirmed){
            $data = [
                        'block_identifier' => "Appointment_booking",
                        'user_id' => $userId,
                        'status' => 0
                    ];
            $appHelper = new AppHelper();
            $updateConversation = $appHelper->createSingleConversation($data);
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
        $rules->add($rules->existsIn(['expert_specialization_service_id'], 'ExpertSpecializationServices'));
        $rules->add($rules->existsIn(['expert_specialization_id'], 'ExpertSpecializations'));
        $rules->add($rules->existsIn(['transaction_id'], 'Transactions'));
        $rules->add($rules->existsIn(['user_card_id'], 'UserCards'));

        return $rules;
    }
}
