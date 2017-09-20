<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Exception\Exception;

/**
 * ExpertSpecializationServices Model
 *
 * @property \App\Model\Table\ExpertsTable|\Cake\ORM\Association\BelongsTo $Experts
 * @property \App\Model\Table\ExpertSpecializationsTable|\Cake\ORM\Association\BelongsTo $ExpertSpecializations
 * @property \App\Model\Table\SpecializationServicesTable|\Cake\ORM\Association\BelongsTo $SpecializationServices
 *
 * @method \App\Model\Entity\ExpertSpecializationService get($primaryKey, $options = [])
 * @method \App\Model\Entity\ExpertSpecializationService newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ExpertSpecializationService[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ExpertSpecializationService|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExpertSpecializationService patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ExpertSpecializationService[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ExpertSpecializationService findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ExpertSpecializationServicesTable extends Table
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

        $this->setTable('expert_specialization_services');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Experts', [
            'foreignKey' => 'expert_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ExpertSpecializations', [
            'foreignKey' => 'expert_specialization_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('SpecializationServices', [
            'foreignKey' => 'specialization_service_id'
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
            ->scalar('price')
            ->requirePresence('price', 'create')
            ->notEmpty('price');

        $validator
            ->scalar('description')
            ->allowEmpty('description');

        $validator
            ->integer('duration')
            ->requirePresence('duration', 'create')
            ->notEmpty('duration');

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
        $rules->add($rules->existsIn(['expert_id'], 'Experts'));
        $rules->add($rules->existsIn(['expert_specialization_id'], 'ExpertSpecializations'));
        $rules->add($rules->existsIn(['specialization_service_id'], 'SpecializationServices'));

        return $rules;
    }

    public function beforeSave($event, $entity, $options){

        $specializationService = $this->ExpertSpecializations
                                ->findById($entity['expert_specialization_id'])
                                ->contain(['Specializations.SpecializationServices' => function($q) use ($entity) {
                                    return $q->where(['id' => $entity['specialization_service_id']]);
                                }])
                                ->first();

        if(!isset($specializationService)){
            throw new Exception(__("No Expert Specialization found with this id."));
        }

        if(!$specializationService->specialization->specialization_services){
            throw new Exception(__('Specialization id and specialization service id do not match.'));
        }
    }
}
