<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Availabilities Model
 *
 * @property \App\Model\Table\ExpertsTable|\Cake\ORM\Association\BelongsTo $Experts
 *
 * @method \App\Model\Entity\Availability get($primaryKey, $options = [])
 * @method \App\Model\Entity\Availability newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Availability[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Availability|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Availability patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Availability[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Availability findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AvailabilitiesTable extends Table
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

        $this->setTable('availabilities');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Experts', [
            'foreignKey' => 'expert_id',
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
            ->dateTime('available_from')
            ->requirePresence('available_from', 'create')
            ->notEmpty('available_from');

        $validator
            ->dateTime('available_to')
            ->requirePresence('available_to', 'create')
            ->notEmpty('available_to');

        $validator
            ->boolean('overlapping_allowed')
            ->requirePresence('overlapping_allowed', 'create')
            ->notEmpty('overlapping_allowed');

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
        $rules->add($rules->existsIn(['expert_id'], 'Experts'));

        return $rules;
    }
}
