<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SalonPayouts Model
 *
 * @property \App\Model\Table\PayoutsTable|\Cake\ORM\Association\BelongsTo $Payouts
 * @property \App\Model\Table\AccountDetailsTable|\Cake\ORM\Association\BelongsTo $AccountDetails
 *
 * @method \App\Model\Entity\SalonPayout get($primaryKey, $options = [])
 * @method \App\Model\Entity\SalonPayout newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SalonPayout[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SalonPayout|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SalonPayout patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SalonPayout[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SalonPayout findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SalonPayoutsTable extends Table
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

        $this->setTable('salon_payouts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('AccountDetails', [
            'foreignKey' => 'account_detail_id',
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
            ->scalar('amount')
            ->requirePresence('amount', 'create')
            ->notEmpty('amount');

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
        $rules->add($rules->existsIn(['account_detail_id'], 'AccountDetails'));

        return $rules;
    }
}
