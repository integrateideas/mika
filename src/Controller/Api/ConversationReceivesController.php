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
        $this->Auth->allow(['index']);
    }

    public function index(){

      if(!$this->request->is(['post'])){
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $phoneNo = $this->request->data['from'];
      $this->loadModel('Users');
      $getExpert = $this->Users->find()->where(['phone' => $phoneNo])->first();
      // pr($getExpert->id);die;
      $this->loadModel('Conversations');
      $findExpertConversation = $this->Conversations->findByUserId($getExpert->id)->last();
      
      if(!$findExpertConversation){
          pr('send sms for not identify the user in conversation');die;
          throw new NotFoundException(__('Your number is not registered with us. Please try again later.'));
      }else{
          
          $appHelper = new AppHelper();
          $reqData = $appHelper->getNextBlock($findExpertConversation->block_identifier,$this->request->data['text']);
          if(!empty($reqData['block_id'])){
            $data = [
                      'block_identifier' => $reqData['block_id'],
                      'user_id' => $getExpert->id,
                      'status' => 0
                    ];
            $updateConversation = $this->Conversations->newEntity();
            $updateConversation = $this->Conversations->patchEntity($updateConversation,$data);
            
            if ($this->Conversations->save($updateConversation)) {
              pr('in save');die;
            }else{
              Log::write('debug',$updateConversation);
              throw new Exception("Error while updating user_salon_id in Experts");
              
            }
            // pr($updateConversation);die;
          }
      }

    }
}
