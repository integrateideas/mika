<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Bandwidth\Bandwidth;
use App\Controller\AppHelper;
use Cake\Log\Log;
use Cake\Datasource\ModelAwareTrait;
/**
 * Conversations Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property |\Cake\ORM\Association\BelongsTo $Appointments
 * @property |\Cake\ORM\Association\BelongsTo $Experts
 *
 * @method \App\Model\Entity\Conversation get($primaryKey, $options = [])
 * @method \App\Model\Entity\Conversation newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Conversation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Conversation|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Conversation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Conversation[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Conversation findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ConversationsTable extends Table
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

        $this->setTable('conversations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('Appointments', [
            'foreignKey' => 'appointment_id',
        ]);
        $this->belongsTo('Experts', [
            'foreignKey' => 'expert_id'
        ]);
         $this->Bandwidth = new Bandwidth();
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
            ->scalar('block_identifier')
            ->requirePresence('block_identifier', 'create')
            ->notEmpty('block_identifier');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['appointment_id'], 'Appointments'));
        $rules->add($rules->existsIn(['expert_id'], 'Experts'));

        return $rules;
    }

     public function afterSave($event,$entity,$options)
    {       
        Log::write('debug',$entity);
        if($entity){
            $experts = $this->loadModel('Experts');
            $expertUserId = $experts->findById($entity->expert_id)->first()->user_id;
            $user = $this->Users->findById($expertUserId)->first();
        }
        Log::write('debug',$user);
        if(isset($user) && !$entity->status){
        // pr($entity);die;
             if($this->sendMessage($entity->block_identifier,$user,$options)){
                $entity->status = true;
                $this->save($entity);
             }
        }
    }

    public function sendMessage($block_id,$user, $msgData, $options = null){
        $msgData = $msgData->offsetGet('msgData');
        $appHelper = new AppHelper();
        $text = $appHelper->getConversationText($block_id,$msgData);
        Log::write('debug',$text);
        Log::write('debug',$user);
        $phoneNumber = $user->phone;
        $phoneNumber  = str_replace('+1', '', $phoneNumber) ;
        // $this->Bandwidth->sendMessage($phoneNumber,$text);
        return true;
    }
}
