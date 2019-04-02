<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ConnectSalonAccounts Model
 *
 * @property \App\Model\Table\StripeUserAccountsTable|\Cake\ORM\Association\BelongsTo $StripeUserAccounts
 * @property \App\Model\Table\UserSalonsTable|\Cake\ORM\Association\BelongsTo $UserSalons
 *
 * @method \App\Model\Entity\ConnectSalonAccount get($primaryKey, $options = [])
 * @method \App\Model\Entity\ConnectSalonAccount newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ConnectSalonAccount[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ConnectSalonAccount|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConnectSalonAccount patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ConnectSalonAccount[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ConnectSalonAccount findOrCreate($search, callable $callback = null, $options = [])
 */
class ConnectSalonAccountsTable extends Table
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

        $this->setTable('connect_salon_accounts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        // $this->belongsTo('StripeUserAccounts', [
        //     'foreignKey' => 'stripe_user_account_id',
        //     'joinType' => 'INNER'
        // ]);
        $this->belongsTo('UserSalons', [
            'foreignKey' => 'user_salon_id',
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
            ->scalar('access_token')
            ->requirePresence('access_token', 'create')
            ->notEmpty('access_token');

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
        // $rules->add($rules->existsIn(['stripe_user_account_id'], 'StripeUserAccounts'));
        $rules->add($rules->existsIn(['user_salon_id'], 'UserSalons'));

        return $rules;
    }
}
