<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ExpertCards Model
 *
 * @property \App\Model\Table\ExpertsTable|\Cake\ORM\Association\BelongsTo $Experts
 * @property \App\Model\Table\StripeCustomersTable|\Cake\ORM\Association\BelongsTo $StripeCustomers
 * @property \App\Model\Table\StripeCardsTable|\Cake\ORM\Association\BelongsTo $StripeCards
 *
 * @method \App\Model\Entity\ExpertCard get($primaryKey, $options = [])
 * @method \App\Model\Entity\ExpertCard newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ExpertCard[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ExpertCard|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExpertCard patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ExpertCard[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ExpertCard findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ExpertCardsTable extends Table
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

        $this->setTable('expert_cards');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Experts', [
            'foreignKey' => 'expert_id',
            'joinType' => 'INNER'
        ]);
        // $this->belongsTo('StripeCustomers', [
        //     'foreignKey' => 'stripe_customer_id',
        //     'joinType' => 'INNER'
        // ]);
        // $this->belongsTo('StripeCards', [
        //     'foreignKey' => 'stripe_card_id',
        //     'joinType' => 'INNER'
        // ]);
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

        $validator
            ->dateTime('is_deleted')
            ->allowEmpty('is_deleted');

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
        // $rules->add($rules->existsIn(['stripe_customer_id'], 'StripeCustomers'));
        // $rules->add($rules->existsIn(['stripe_card_id'], 'StripeCards'));

        return $rules;
    }
}
