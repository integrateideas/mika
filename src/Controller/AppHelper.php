<?php
/**
* CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
* Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
*
* Licensed under The MIT License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
* @link      http://cakephp.org CakePHP(tm) Project
* @since     0.2.9
* @license   http://www.opensource.org/licenses/mit-license.php MIT License
*/
namespace App\Controller;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use Cake\Network\Exception\NotFoundException;
use Cake\Core\Exception\Exception;
use Cake\Controller\Controller;
use Cake\Datasource\ModelAwareTrait;
use App\Controller\Api\AppointmentsController;
use Cake\Collection\Collection;
/**
* Application Controller
*
* Add your application-wide methods in the class below, your controllers
* will inherit them.
*
* @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
*/
class AppHelper
{
  use ModelAwareTrait;

  private static $notificationsArray = [
            "scheduling_availabilities" => [
                                              "short_title" => 'Schedule Availability',
                                              "body" => "Would you like to make yourself available on Mika today?"
                                            ],
                      "confirm_schedule" => [
                                              "short_title" => "Availability Confirmed",
                                              "body" => "Your availability has been updated."
                                            ],
                   "appointment_booking" => [ 
                                              "short_title" => "New Booking Request",
                                              "body" => "A new booking request has arrived."
                                            ],
                       "confirm_booking" => [
                                              "short_title" => "Booking Confirmed",
                                              "body" => "Your booking has been confirmed."
                                            ],
                       "reject_booking" => [
                                              "short_title" => "Booking Rejected",
                                              "body" => "Your booking has been rejected."
                                            ],
                       "cancel_booking" => [
                                              "short_title" => "Booking Cancelled",
                                              "body" => "Your booking has been cancelled."
                                            ]          
  ];

  private static $conversationArray = [
                                "scheduling_availabilities" =>[
                                                                "text"=>"Good morning. Would you like to make yourself available on Mika today? Ignore this text to cancel.", 
                                                                "response"=>[
                                                                        [
                                                                            "intent" => ['Yes','Y','Ya','yes','y','ya'],
                                                                            "block_identifier" => "add_schedule"
                                                                        ],
                                                                        [
                                                                            "intent" => ['No','Na','N','no','n','na'],
                                                                            "block_identifier" => "availability_not_updated"
                                                                        ]
                                                                    ]
                                                                    
                                                                ],

                                    "add_schedule" => [
                                                              "text"=>"Great. Here is a quick link where you can update your availability. {{ deepLink}}"
                                                      ],
                                    "availability_updated" => [
                                                                    "text"=>"Thanks for updating your availability for today. I will reach out to you if there are any bookings."
                                                              ],
                                    "availability_not_updated" => [
                                                                    "text"=>"It looks like you haven't updated your time, are you no longer available for a booking today?", 
                                                                "response"=>[
                                                                        [
                                                                            "intent" => ['Yes','Y','Ya','yes','y','ya'],
                                                                            "block_identifier" => "confirm_availability"
                                                                        ],
                                                                        [
                                                                            "intent" => ['No','Na','N','no','n','na'],
                                                                            "block_identifier" => "confirm_not_available"
                                                                        ]
                                                                    ]
                                                                ],                                                                
                                    "confirm_availability" => [
                                                                    "text"=>"Okay, hope you have a nice day."
                                                                ],
                                    "confirm_not_available" => [
                                                                    "text"=>"Okay, hope you have a nice day."
                                                                ],                                                                
                                    "appointment_booking_request" => [
                                                                "text"=>"Hey, {{custName}} is looking to book {{serviceName}} at {{reqTime}} today. Click this {{bookingConfirmationLink}} to confirm. Ignore this text to cancel",
                                                                "response"=>[
                                                                        [
                                                                            "intent" => ['Yes','Yo','Ya','Yup'],
                                                                            "block_identifier" => "confirm_booking",
                                                                            'api'=>'confirmBooking'
                                                                        ],
                                                                        [
                                                                            "intent" => ['No','Na','Nops','N'],
                                                                            "block_identifier" => "booking_deny",
                                                                            'api'=>'denyBooking'
                                                                        ]
                                                                    ]
                                                                   
                                                                ],
                                    "confirm_booking" => [
                                                                "text"=>"Your booking with {{custName}} for {{serviceName}} at  {{reqTime}} is confirmed."
                                                                ],
                                    "booking_deny" => [
                                                                "text"=>"Your booking with {{custName}} for {{serviceName}} at  {{reqTime}} is cancelled."
                                                                ]

                             ];
    
    public function getNotificationText($blockIdentifier){
      if(isset(self::$notificationsArray[$blockIdentifier])){
        if(isset(self::$notificationsArray[$blockIdentifier]['short_title'])){
          $content = ['title' => self::$notificationsArray[$blockIdentifier]['short_title'],'body' => self::$notificationsArray[$blockIdentifier]['body']];

          return $content;
        }
      }
    }

    public function createManyConversation($data){
        $conversations = TableRegistry::get('Conversations');
        $msgData = (new Collection($data))->extract('msg_data')->toArray();
        if(count($msgData) !== count($data)){
          print_r('Resend the data.');die;
        }
        $newEntities = $conversations->newEntities($data);

        $patchEntities = $conversations->patchEntities($newEntities,$data);
        if ($conversations->saveMany($patchEntities,['msgData'=>$msgData,'is_multiple'=>true])){
            Log::write('debug','Conversations have been saved ');
            Log::write('debug',$patchEntities);
            return $patchEntities;
        }else{
            throw new Exception("Error Processing Request while saving data.");
        }
        
    }
    public function createSingleConversation($data){
        $reqData =  [
                        'block_identifier' => $data['block_identifier'],
                        'user_id' => (isset($data['user_id'])?$data['user_id']:null),
                        'expert_id' => $data['expertId'],
                        'status' => $data['status'],
                        'appointment_id' => (isset($data['appointmentId'])?$data['appointmentId']:null)
                    ];
        $msgData =  [
                        'expertName' =>(isset($data['expertName'])?$data['expertName']:null),
                        'custName' => (isset($data['custName'])?$data['custName']:null),
                        'serviceName' => (isset($data['serviceName'])?$data['serviceName']:null),
                        'reqTime'=> (isset($data['reqTime'])?$data['reqTime']:null)
                    ];
        
        $conversations = TableRegistry::get('Conversations');
        $newEntity = $conversations->newEntity($reqData);
        $patchEntity = $conversations->patchEntity($newEntity,$reqData);

        if ($conversations->save($patchEntity,['msgData' => $msgData])){

            Log::write('debug','Single Conversation has been saved ');
            Log::write('debug',$patchEntity);
            return $patchEntity;
        }else{
            Log::write('error',$patchEntity->errors());
            throw new Exception("Error Processing Request while saving data.");
        }
        
    }

     private function __substitute($content, $hash){
       //write substitute logic
       $i=0;
       $afterStr = false;
       if(!is_array($hash)){
        return $content;
       }
       foreach ($hash as $key => $value) {
           if(!is_array($value)){
               $placeholder = sprintf('{{%s}}', $key);
               if($placeholder=="{{".$key."}}"){
                   if(!$i){
                       $afterStr = str_replace($placeholder, $value, $content);
                   }else{
                       $afterStr = str_replace($placeholder, $value, $afterStr);
                   }
                   $i++;
               }
           }
       }
       return $afterStr;
   }

    public function getConversationText($blockIdentifier,$msgData = null){
      if(isset(self::$conversationArray[$blockIdentifier])){
        if(isset(self::$conversationArray[$blockIdentifier]['text'])){
            $content = $this->__substitute(self::$conversationArray[$blockIdentifier]['text'],$msgData);
          return $content;
        }else{
          throw new NotFoundException(__('No text found for this Block Identifier.'));  
        }
      } else{
        throw new NotFoundException(__("This block identifier doesn't exist."));
      }   
    }

    public function getNextBlock($conversation,$intent){
      $blockIdentifier = $conversation->block_identifier;
      // pr($blockIdentifier);die;
      $conversationResponses = (isset(self::$conversationArray[$blockIdentifier]['response'])?self::$conversationArray[$blockIdentifier]['response']:null);
      if(!$conversationResponses){
        return false;
      }
      // pr($conversationResponses);
      foreach ($conversationResponses as $key => $value) {
// pr($value);die;
          if(isset($value['intent'])){
            if(in_array($intent,$value['intent'])){
              $newBlockId =  (isset($value['block_identifier']))?$value['block_identifier']:null;
            }else{
              $newBlockId = null;
            }  
            
          }
          //if we need to hit any api
          if(isset($value['api'])){
            $this->_callApis($conversation,$value['api']);
          }
          if($newBlockId && (isset(self::$conversationArray[$newBlockId]))){
            if(isset(self::$conversationArray[$newBlockId]['text'])){
              return ['text' => self::$conversationArray[$newBlockId]['text'], 'block_id' => $newBlockId];
            }else{
              throw new NotFoundException(__('No text found for this Block Identifier.'));  
            }
          }
      }  
    }

    private function _callApis($conversation,$value){

      switch ($value) {
        case 'confirmBooking':
        $this->loadModel('Appointments');
        $appointment = $this->Appointments->findById($conversation->appointment_id)
                                          ->contain(['Users','AppointmentServices.ExpertSpecializationServices.SpecializationServices'])
                                          // ->where([])
                                          ->first();
        
        $updateBookingStatus = [
                                    'is_confirmed' => 1
                                ];
        $appointment = $this->Appointments->patchEntity($appointment,$updateBookingStatus);
        if (!$this->Appointments->save($appointment)) {
          if($appointment->errors()){
            $this->_sendErrorResponse($appointment->errors());
          }
          throw new Exception("Error Processing Request");
        }
      }
    }

}
