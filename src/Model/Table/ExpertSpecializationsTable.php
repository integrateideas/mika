<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ExpertSpecializations Model
 *
 * @property \App\Model\Table\ExpertsTable|\Cake\ORM\Association\BelongsTo $Experts
 * @property \App\Model\Table\SpecializationsTable|\Cake\ORM\Association\BelongsTo $Specializations
 * @property \App\Model\Table\ExpertSpecializationServicesTable|\Cake\ORM\Association\HasMany $ExpertSpecializationServices
 *
 * @method \App\Model\Entity\ExpertSpecialization get($primaryKey, $options = [])
 * @method \App\Model\Entity\ExpertSpecialization newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ExpertSpecialization[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ExpertSpecialization|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExpertSpecialization patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ExpertSpecialization[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ExpertSpecialization findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ExpertSpecializationsTable extends Table
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

        $this->setTable('expert_specializations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Experts', [
            'foreignKey' => 'expert_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Specializations', [
            'foreignKey' => 'specialization_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ExpertSpecializationServices', [
            'foreignKey' => 'expert_specialization_id'
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
            ->scalar('description')
            ->allowEmpty('description');

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
        $rules->add($rules->existsIn(['specialization_id'], 'Specializations'));

        return $rules;
    }
}
