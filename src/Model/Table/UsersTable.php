<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Collection\Collection;

/**
 * Users Model
 *
 * @property \App\Model\Table\RolesTable|\Cake\ORM\Association\BelongsTo $Roles
 * @property |\Cake\ORM\Association\HasMany $Appointments
 * @property \App\Model\Table\ExpertsTable|\Cake\ORM\Association\HasMany $Experts
 * @property \App\Model\Table\SocialConnectionsTable|\Cake\ORM\Association\HasMany $SocialConnections
 * @property |\Cake\ORM\Association\HasMany $UserFavouriteExperts
 * @property \App\Model\Table\UserSalonsTable|\Cake\ORM\Association\HasMany $UserSalons
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Appointments', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Experts', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('SocialConnections', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('UserFavouriteExperts', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('UserSalons', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('UserDeviceTokens', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('UserCards', [
            'foreignKey' => 'user_id'
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
            ->scalar('first_name')
            ->requirePresence('first_name', 'create')
            ->notEmpty('first_name');

        $validator
            ->scalar('last_name')
            ->requirePresence('last_name', 'create')
            ->notEmpty('last_name');

        $validator
            ->email('email')
            ->allowEmpty('email');

        $validator
            ->scalar('password')
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->scalar('phone')
            // ->requirePresence('phone', 'create')
            ->allowEmpty('phone');

        $validator
            ->dateTime('is_deleted')
            ->allowEmpty('is_deleted');

        $validator
            ->scalar('username')
            ->requirePresence('username', 'create')
            ->notEmpty('username');

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
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->existsIn(['role_id'], 'Roles'));

        return $rules;
    }

    public function loginExpertInfo($id){

      $user = $this->find()
                    ->where(['id' => $id])
                    ->contain(['Experts.ExpertSpecializations'  => function($q){
                        return $q->contain(['ExpertSpecializationServices.SpecializationServices','Specializations']);
                      },'SocialConnections','UserCards','Experts.Appointments' => function($q){
                        return  $q->where(['is_confirmed IS NULL'])
                                  ->contain(['ExpertSpecializations.Specializations','ExpertSpecializationServices.SpecializationServices']);
                      }])
                    ->first();

      if(isset($user['experts']) && $user['experts'] != []){  

        if($user['experts'][0]['expert_specializations'] != []){

          $collection = $user['experts'][0]['expert_specializations'];
          $coll = [];
          foreach ($collection as $key => $specialization) {

                $coll[$specialization->specialization_id] = (new Collection($specialization->expert_specialization_services))->combine('specialization_service_id','id')->toArray();
          }
         
          $user['selected_specializations'] = $coll; 
        }


        if(isset($user['experts'][0]['appointments']) && $user['experts'][0]['appointments']){

          //Grouping pending appointments according to the available slots.
          $pendingAppointmentsCollection = new Collection($user['experts'][0]['appointments']);
          $groupedPendingAppointments = $pendingAppointmentsCollection->groupBy('expert_availability_id')
                                                                    ->toArray();
          $data['data']['groupedPendingAppointments'] = $groupedPendingAppointments;

          //Reducing the pending appointments array to the one appointment that came the earliest.
          $earliestPendingAppointment = $pendingAppointmentsCollection->min('created');
          $data['data']['earliestPendingAppointment'] = $earliestPendingAppointment;
        }
        $data['data']['expertSpecializations'] = $user['experts'][0]['expert_specializations'];
      }

      $return['user'] = $user;

      if(isset($data)){
        $return['data'] = $data;
      }

      return $return;

    }
}
