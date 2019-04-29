<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Controller\AppHelper;
use App\Bandwidth\Bandwidth;
use Cake\Datasource\ModelAwareTrait;
use Cake\Log\Log;
use Cake\ORM\Rule\IsUnique;
/**
 * ExpertAvailabilities Model
 *
 * @property \App\Model\Table\ExpertsTable|\Cake\ORM\Association\BelongsTo $Experts
 * @property \App\Model\Table\AppointmentsTable|\Cake\ORM\Association\HasMany $Appointments
 *
 * @method \App\Model\Entity\ExpertAvailability get($primaryKey, $options = [])
 * @method \App\Model\Entity\ExpertAvailability newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ExpertAvailability[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ExpertAvailability|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExpertAvailability patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ExpertAvailability[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ExpertAvailability findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ExpertAvailabilitiesTable extends Table
{
 use ModelAwareTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('expert_availabilities');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Experts', [
            'foreignKey' => 'expert_id',
            'joinType' => 'INNER'
            ]);
        $this->hasMany('Appointments', [
            'foreignKey' => 'expert_availability_id'
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
        ->dateTime('available_from')
        ->requirePresence('available_from', 'create')
        ->notEmpty('available_from');

        $validator
        ->dateTime('available_to')
        ->requirePresence('available_to', 'create')
        ->notEmpty('available_to');

        // $validator
        // ->boolean('overlapping_allowed')
        // ->requirePresence('overlapping_allowed', 'create')
        // ->notEmpty('overlapping_allowed');

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
        $rules->add($rules->existsIn(['expert_id'], 'Experts'));
        $rules->add($rules->isUnique(
            ['expert_id', 'available_from', 'available_to'],
            'This slot has already been used.'
        ));

        return $rules;
    }

    // public function afterSave($event,$entity, $options)
    // {   

    //     $this->Bandwidth = new Bandwidth();
    //     $this->loadModel('Experts');
    //     $expertData = $this->Experts->find()->contain(['Users'])->where(['Experts.id'=>$entity->expert_id])->first();
    //     $phoneNumber = $expertData->user->phone;
    //     $appHelper = new AppHelper();
    //     $text = $appHelper->getConversationText("availability_updated");
    //     $this->Bandwidth->sendMessage($phoneNumber,$text);       
        
    // }

}
