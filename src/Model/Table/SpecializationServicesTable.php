<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SpecializationServices Model
 *
 * @property \App\Model\Table\SpecializationsTable|\Cake\ORM\Association\BelongsTo $Specializations
 * @property \App\Model\Table\ExpertSpecializationServicesTable|\Cake\ORM\Association\HasMany $ExpertSpecializationServices
 *
 * @method \App\Model\Entity\SpecializationService get($primaryKey, $options = [])
 * @method \App\Model\Entity\SpecializationService newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SpecializationService[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SpecializationService|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SpecializationService patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SpecializationService[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SpecializationService findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SpecializationServicesTable extends Table
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

        $this->setTable('specialization_services');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Specializations', [
            'foreignKey' => 'specialization_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ExpertSpecializationServices', [
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
            ->scalar('name')
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('label')
            ->requirePresence('label', 'create')
            ->notEmpty('label');

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
        $rules->add($rules->existsIn(['specialization_id'], 'Specializations'));

        return $rules;
    }
}
