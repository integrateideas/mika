<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Http\Client;
use Cake\Core\Configure;

/**
 * FCMNotification component
 */
class FCMNotificationComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function sendToExpertApp($title, $body, $to, $data = []){

    	$authorization = 'key='.(Configure::read('FCMserverKey.expert'));
    	
    	return $this->_sendNotification($authorization, $title, $body, $to, $data);
    }

    public function sendToUserApp($title, $body, $to, $data = []){

    	$authorization = 'key='.(Configure::read('FCMserverKey.user'));

    	return $this->_sendNotification($authorization, $title, $body, $to, $data);
    }

    //$to could either be a device token or topic.
    //$data will be an array
    private function _sendNotification($authorization, $title, $body, $to, $data = []){

    	$url = 'https://fcm.googleapis.com/fcm/send';
	
    	$payload = [
					"notification" => [
					    "title" => $title,  //Any value
					    "body" => $body,  //Any value
					    "sound" => "default", //If you want notification sound
					    "click_action" => "FCM_PLUGIN_ACTIVITY",  //Must be present for Androide
						],
					"data" => $data,
				    "to" =>  $to,
				    "priority" => "high", //If not set, notification won't be delivered on completely closed iOS app
				    // "restricted_package_name" => "com.ionicframework.exampleproject767247" //Optional. Set for application filtering
				   	];

		$http = new Client(['headers' => ['Authorization' => $authorization,'Content-Type' => 'application/json']]);
    	$response = $http->post($url, json_encode($payload));
        
        return json_decode($response->body()); 
	}
}
