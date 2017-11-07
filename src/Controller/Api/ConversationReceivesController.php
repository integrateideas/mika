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
use Cake\Log\Log;;

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
        $this->Auth->allow(['receiveResponse']);
    }

    public function index(){

      if(!$this->request->is(['post'])){
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $phoneNo = $this->request->data['from'];
      $getExpert = $this->Users->find()->where(['phone' => $phoneNo])->first();
      $this->loadModel('Conversations');
      $findExpertConversation = $this->Conversations->findByUserId($getExpert->id)->first();
      
      if(!$findExpertConversation){
          pr('send sms for not identify the user in conversation');die;
          throw new NotFoundException(__('Your number is not registered with us. Please try again later.'));
      }else{
          pr($findExpertConversation);die;
      }

    }
}
