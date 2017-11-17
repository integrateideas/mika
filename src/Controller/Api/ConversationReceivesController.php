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
      $this->loadModel('Users');
      $getUser = $this->Users->find()->where(['phone' => $phoneNo])->first();
      
      if(!$getUser){
         throw new NotFoundException(__('Your number is not registered with us. So we are not able to identify you.')); 
      }
      Log::write('debug',$getUser);
      $this->loadModel('Conversations');
      $findExpertConversation = $this->Conversations->findByUserId($getUser->id)->last();
      
      Log::write('debug',$findExpertConversation);
        if(!$findExpertConversation){
            throw new NotFoundException(__('No conversation exist with this expert.'));
        }else{
            
            $appHelper = new AppHelper();
            $reqData = $appHelper->getNextBlock($findExpertConversation->block_identifier,$this->request->data['text']);
            
            if(!empty($reqData['block_id'])){
              $data = [
                        'block_identifier' => $reqData['block_id'],
                        'user_id' => $getUser->id,
                        'status' => 0,
                        'expertName' => $getUser->first_name,
                        'serviceName' => ''
                       ];

              $updateConversation = $appHelper->createSingleConversation($data);
            }
        }      

      $this->set('data',$updateConversation);
      $this->set('status',true);
      $this->set('_serialize', ['status','data']);
    }

    public function fallback(){
      Log::write('debug','in fallback function');
      Log::write('debug',$this->request->data);  
    }
}
