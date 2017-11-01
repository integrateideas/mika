<?php 
namespace App\Event;

use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Cake\Mailer\MailerAwareTrait;
use Cake\Network\Exception;
use Cake\Controller\Controller;


class MessageEventListener implements EventListenerInterface {

	public function __construct(){
		$controller = new Controller();
		$this->Bandwidth = $controller->loadComponent('Bandwidth');
		$this->Conversations = $controller->loadModel('Conversations');
	}

	public function implementedEvents()
	{
	    return [
	        'SendMessages.user.details' => 'sendSms'
	    ];
	}

	public function sendSms($event, $data){
	
		$arr = [];					
		foreach ($data as $key => $value) {
			
			$arr[] = [
						'user_id' => $value->id,
						'block_identifier' => 'Scheduling',
						'status' => 1
					];	
		}
		
		$sendSms = $this->Conversations->newEntities($arr);
		$sendSms = $this->Conversations->patchEntities($sendSms,$arr);
		
		if ($this->Conversations->saveMany($sendSms)){
        	pr($sendSms);die;
        }
		// $phoneNo = "7573507080";
		// $this->Bandwidth->sendMessage($phoneNo,"Test Message");
	}



}

?>