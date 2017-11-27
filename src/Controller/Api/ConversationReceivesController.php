<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Core\Exception\Exception;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Auth\DefaultPasswordHasher;
use Firebase\JWT\JWT;
use Cake\Utility\Security;
use Cake\I18n\Time;
use Cake\Core\Configure;
use Cake\Log\Log;
use App\Controller\AppHelper;

/**
 * Appointments Controller
 *
 * @property \App\Model\Table\AppointmentsTable $Appointments
 *
 * @method \App\Model\Entity\Appointment[] paginate($object = null, array $settings = [])
 */
class ConversationReceivesController extends ApiController
{


    /**
     * View method
     *
     * @param string|null $id Appointment id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

    public function initialize()
    {
      parent::initialize();
      $this->Auth->allow(['add','fallback']);
    }

    public function add(){

      Log::write('debug',$this->request->data);
      if(!$this->request->is(['post'])){
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $phoneNo = $this->request->data['from'];
      // $phoneNo = str_replace("+1","",$phoneNo);
      $this->loadModel('Users');
      $getUser = $this->Users->find()->contain(['Experts'])->where(['phone' => $phoneNo])->first();
      // pr($getUser);die;
      if(!$getUser){
       throw new NotFoundException(__('Your number is not registered with us. So we are not able to identify you.')); 
     }
     Log::write('debug',$getUser);
     $this->loadModel('Conversations');
     $findExpertConversation = $this->Conversations->findByExpertId($getUser->experts[0]->id)
     ->contain(['Users','Experts.Users','Appointments.ExpertAvailabilities'])
     ->last();
      // pr($findExpertConversation);die;
     Log::write('debug',$findExpertConversation);
     if(!$findExpertConversation){
      throw new NotFoundException(__('No conversation exist with this expert.'));
    }else{
            // pr($findExpertConversation);die;
      $appHelper = new AppHelper();
      $reqData = $appHelper->getNextBlock($findExpertConversation,$this->request->data['text']);
            // pr($findExpertConversation);die;
      if($reqData && isset($reqData['block_id']) &&!empty($reqData['block_id'])){
        $data = [
        'block_identifier' => $reqData['block_id'],
        'user_id' => $findExpertConversation->user_id,
        'status' => 0,
        'expertName' => $getUser->first_name,
        'expertId' => $findExpertConversation->expert_id,
        ];
        if(!empty($findExpertConversation->user)){
          $data['custName'] = $findExpertConversation->user->first_name. ' '.(isset($findExpertConversation->user->last_name)?$findExpertConversation->user->last_name:'');
        }
        if(!empty($findExpertConversation->appointment)){
          $data['appointmentId'] = $findExpertConversation->appointment_id;
          $data['reqTime'] = $findExpertConversation->appointment->expert_availability->available_from;
          $data['serviceName'] = 'hair color';
        }
       
        
        $updateConversation = $appHelper->createSingleConversation($data);
        $this->set('data',$updateConversation);
        $this->set('status',true);
        $this->set('_serialize', ['status','data']);
      }else{
        Log::write('debug','unable to get next block');
        $this->set('status',true);
        $this->set('_serialize', ['status']);
      }
    }      


  }

  public function fallback(){
    Log::write('debug','in fallback function');
    Log::write('debug',$this->request->data);  
  }
}
