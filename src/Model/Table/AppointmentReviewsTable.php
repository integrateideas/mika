<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AppointmentReviews Model
 *
 * @property \App\Model\Table\ExpertsTable|\Cake\ORM\Association\BelongsTo $Experts
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\AppointmentsTable|\Cake\ORM\Association\BelongsTo $Appointments
 *
 * @method \App\Model\Entity\AppointmentReview get($primaryKey, $options = [])
 * @method \App\Model\Entity\AppointmentReview newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AppointmentReview[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AppointmentReview|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AppointmentReview patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AppointmentReview[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AppointmentReview findOrCreate($search, callable $callback = null, $options = [])
 */
class AppointmentReviewsTable extends Table
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

        $this->setTable('appointment_reviews');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Experts', [
            'foreignKey' => 'expert_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
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
            ->integer('rating')
            ->requirePresence('rating', 'create')
            ->notEmpty('rating');

        $validator
            ->scalar('review')
            ->requirePresence('review', 'create')
            ->notEmpty('review');

        $validator
            ->boolean('is_approved')
            ->requirePresence('is_approved', 'create')
            ->notEmpty('is_approved');

        $validator
            ->boolean('is_deleted')
            ->requirePresence('is_deleted', 'create')
            ->notEmpty('is_deleted');

        $validator
            ->boolean('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->scalar('remark')
            ->allowEmpty('remark');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['appointment_id'], 'Appointments'));

        return $rules;
    }
}
