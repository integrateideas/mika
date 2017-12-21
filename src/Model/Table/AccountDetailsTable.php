<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AccountDetails Model
 *
 * @method \App\Model\Entity\AccountDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\AccountDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AccountDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AccountDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AccountDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AccountDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AccountDetail findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AccountDetailsTable extends Table
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

        $this->setTable('account_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->scalar('account_holder_name')
            ->requirePresence('account_holder_name', 'create')
            ->notEmpty('account_holder_name');

        $validator
            ->integer('account_number')
            ->requirePresence('account_number', 'create')
            ->notEmpty('account_number');

        $validator
            ->scalar('bank_code')
            ->requirePresence('bank_code', 'create')
            ->notEmpty('bank_code');

        $validator
            ->scalar('branch_name')
            ->requirePresence('branch_name', 'create')
            ->notEmpty('branch_name');

        return $validator;
    }
}
