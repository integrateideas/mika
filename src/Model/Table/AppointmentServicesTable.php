<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AppointmentServices Model
 *
 * @property \App\Model\Table\AppointmentsTable|\Cake\ORM\Association\BelongsTo $Appointments
 * @property \App\Model\Table\ExpertSpecializationsTable|\Cake\ORM\Association\BelongsTo $ExpertSpecializations
 * @property \App\Model\Table\ExpertSpecializationServicesTable|\Cake\ORM\Association\BelongsTo $ExpertSpecializationServices
 *
 * @method \App\Model\Entity\AppointmentService get($primaryKey, $options = [])
 * @method \App\Model\Entity\AppointmentService newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AppointmentService[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AppointmentService|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AppointmentService patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AppointmentService[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AppointmentService findOrCreate($search, callable $callback = null, $options = [])
 */
class AppointmentServicesTable extends Table
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

        $this->setTable('appointment_services');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Appointments', [
            'foreignKey' => 'appointment_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ExpertSpecializations', [
            'foreignKey' => 'expert_specialization_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ExpertSpecializationServices', [
            'foreignKey' => 'expert_specialization_service_id',
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
            ->boolean('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        return $validator;
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
        $rules->add($rules->existsIn(['appointment_id'], 'Appointments'));
        $rules->add($rules->existsIn(['expert_specialization_id'], 'ExpertSpecializations'));
        $rules->add($rules->existsIn(['expert_specialization_service_id'], 'ExpertSpecializationServices'));

        return $rules;
    }
}
