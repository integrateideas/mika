<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
/**
 * Conversations Controller
 *
 * @property \App\Model\Table\ConversationsTable $Conversations
 *
 * @method \App\Model\Entity\Conversation[] paginate($object = null, array $settings = [])
 */
class ConversationsController extends AppController
{

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */


    // public function sendMessage($block_id, $user, $options = null){

    //     $conversationArray = [];

    //     $users = $this->Users->find()->where(['role_id' => 3])->all()->indexBy('id')->toArray();

    //         foreach ($users as $key => $value) {

    //         $data[] = [
    //                     'user_id' => $value->id,
    //                     'block_identifier' => 'Scheduling Availabilities',
    //                     'status' => 1
    //                 ];  
    //         }
    //     $sendSms = $this->Conversations->newEntities($data);
    //     $sendSms = $this->Conversations->patchEntities($sendSms,$data);

    //     if ($this->Conversations->saveMany($sendSms,['users' => $users])){
    //         pr($sendSms);die;
    //     }
    // }

   

    // Event Fire function 
    // protected function _fireEvent($name, $data){
    
    //     $name = 'SendMessages.'.$name;
    //     $event = new Event($name, $this, [
    //             $name => $data
    //         ]);
    //     $this->eventManager()->dispatch($event);     
    // }
}
