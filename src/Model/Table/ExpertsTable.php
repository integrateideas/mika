<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Experts Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\UserSalonsTable|\Cake\ORM\Association\BelongsTo $UserSalons
 * @property |\Cake\ORM\Association\HasMany $Appointments
 * @property \App\Model\Table\ExpertAvailabilitiesTable|\Cake\ORM\Association\HasMany $ExpertAvailabilities
 * @property \App\Model\Table\ExpertCardsTable|\Cake\ORM\Association\HasMany $ExpertCards
 * @property \App\Model\Table\ExpertLocationsTable|\Cake\ORM\Association\HasMany $ExpertLocations
 * @property \App\Model\Table\ExpertSpecializationServicesTable|\Cake\ORM\Association\HasMany $ExpertSpecializationServices
 * @property \App\Model\Table\ExpertSpecializationsTable|\Cake\ORM\Association\HasMany $ExpertSpecializations
 *
 * @method \App\Model\Entity\Expert get($primaryKey, $options = [])
 * @method \App\Model\Entity\Expert newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Expert[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Expert|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Expert patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Expert[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Expert findOrCreate($search, callable $callback = null, $options = [])
 */
class ExpertsTable extends Table
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

        $this->setTable('experts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('UserSalons', [
            'foreignKey' => 'user_salon_id'
        ]);
        $this->hasMany('Appointments', [
            'foreignKey' => 'expert_id'
        ]);
        $this->hasMany('ExpertAvailabilities', [
            'foreignKey' => 'expert_id'
        ]);
        $this->hasMany('ExpertCards', [
            'foreignKey' => 'expert_id'
        ]);
        $this->hasMany('ExpertLocations', [
            'foreignKey' => 'expert_id'
        ]);
        $this->hasMany('ExpertSpecializationServices', [
            'foreignKey' => 'expert_id'
        ]);
        $this->hasMany('ExpertSpecializations', [
            'foreignKey' => 'expert_id'
        ]);
        $this->hasMany('UserFavouriteExperts', [
            'foreignKey' => 'expert_id'
        ]);
         $this->hasMany('Conversations', [
            'foreignKey' => 'expert_id'
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
            ->scalar('timezone')
            ->requirePresence('timezone', 'create')
            ->notEmpty('timezone');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['user_salon_id'], 'UserSalons'));

        return $rules;
    }
}
