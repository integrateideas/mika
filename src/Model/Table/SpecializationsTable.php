<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Specializations Model
 *
 * @property \App\Model\Table\ExpertSpecializationsTable|\Cake\ORM\Association\HasMany $ExpertSpecializations
 * @property \App\Model\Table\SpecializationServicesTable|\Cake\ORM\Association\HasMany $SpecializationServices
 *
 * @method \App\Model\Entity\Specialization get($primaryKey, $options = [])
 * @method \App\Model\Entity\Specialization newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Specialization[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Specialization|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Specialization patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Specialization[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Specialization findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SpecializationsTable extends Table
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

        $this->setTable('specializations');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('ExpertSpecializations', [
            'foreignKey' => 'specialization_id'
        ]);
        $this->hasMany('SpecializationServices', [
            'foreignKey' => 'specialization_id'
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
}
