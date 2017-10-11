<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AppointmentTransactions Model
 *
 * @property \App\Model\Table\AppointmentsTable|\Cake\ORM\Association\BelongsTo $Appointments
 * @property \App\Model\Table\ChargesTable|\Cake\ORM\Association\BelongsTo $Charges
 *
 * @method \App\Model\Entity\AppointmentTransaction get($primaryKey, $options = [])
 * @method \App\Model\Entity\AppointmentTransaction newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AppointmentTransaction[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AppointmentTransaction|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AppointmentTransaction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AppointmentTransaction[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AppointmentTransaction findOrCreate($search, callable $callback = null, $options = [])
 */
class AppointmentTransactionsTable extends Table
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

        $this->setTable('appointment_transactions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Appointments', [
            'foreignKey' => 'appointment_id',
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
            ->integer('transaction_amount')
            ->requirePresence('transaction_amount', 'create')
            ->notEmpty('transaction_amount');

        $validator
            ->boolean('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->scalar('remark')
            ->requirePresence('remark', 'create')
            ->notEmpty('remark');

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

        return $rules;
    }
}
