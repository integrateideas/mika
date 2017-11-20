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

  private static $conversationArray = [
                                "Scheduling_Availabilities" =>[
                                                                "text"=>"Good morning, {{expertName}}. Would you like to make yourself available on Mika today? Ignore this text to cancel.", 
                                                                "response"=>[
                                                                        [
                                                                            "intent" => ['Yes','Y','Ya','yes','y','ya'],
                                                                            "block_identifier" => "confirm_schedule"
                                                                        ],
                                                                        [
                                                                            "intent" => ['No','Na','N','no','n','na'],
                                                                            "block_identifier" => "Availability_not_updated"
                                                                        ]
                                                                    ]
                                                                    
                                                                ],

                                    "confirm_schedule" => [
                                                                "text"=>"Great. Here is a quick link where you can update your availability.", 
                                                                "response"=>[
                                                                        [
                                                                            "intent" => [],
                                                                            "block_identifier" => "Availability_updated"
                                                                        ],
                                                                        [
                                                                            "intent" => [],
                                                                            "block_identifier" => "Availability_not_updated"
                                                                        ]
                                                                    ]
                                                                   
                                                                ],
                                    "Availability_not_updated" => [
                                                                    "text"=>"It looks like you haven't updated your time, are you no longer available for a booking today?", 
                                                                "response"=>[
                                                                        [
                                                                            "intent" => ['No','Na','N','no','n','na'],
                                                                            "block_identifier" => "confirm_not_available"
                                                                        ]
                                                                    ]
                                                                ],
                                    "confirm_not_available" => [
                                                                    "text"=>"Okay, hope you have a nice day. ", 
                                                                "response"=>[
                                                                        [
                                                                            "intent" => [],
                                                                            "block_identifier" => "Availability_not_updated"
                                                                        ]
                                                                    ]
                                                                ],                                                                
                                    "Appointment_booking" => [
                                                                "text"=>"{{expertName}}, would you like to confirm an appointment for {{serviceName}}", 
                                                                "response"=>[
                                                                        [
                                                                            "intent" => ['Yes','Yo','Ya','Yup'],
                                                                            "block_identifier" => "Confirm_booking",
                                                                            'api'=>['zcaca',
                                                                            'data'=>[expertName]]
                                                                        ],
                                                                        [
                                                                            "intent" => ['No','Na','Nops','N'],
                                                                            "block_identifier" => "Booking_deny"
                                                                        ]
                                                                    ]
                                                                   
                                                                ],
                                    "Confirm_booking" => [
                                                                "text"=>"Thanks {{expertName}}, for the confirmation. We will inform the customer for your booking accepatance", 
                                                                "response"=>[
                                                                        [
                                                                            "intent" => ['Yes','Yo','Ya','Yup'],
                                                                            "block_identifier" => "Availability_updated"
                                                                        ],
                                                                        [
                                                                            "intent" => ['No','Na','Nops','N'],
                                                                            "block_identifier" => "Availability_not_updated"
                                                                        ]
                                                                    ]
                                                                   
                                                                ],
                                    "Booking_deny" => [
                                                                "text"=>"You deny the booking. We will inform the customer for your denial", 
                                                                "response"=>[
                                                                        [
                                                                            "intent" => ['Yes','Yo','Ya','Yup'],
                                                                            "block_identifier" => "Availability_updated"
                                                                        ],
                                                                        [
                                                                            "intent" => ['No','Na','Nops','N'],
                                                                            "block_identifier" => "Availability_not_updated"
                                                                        ]
                                                                    ]
                                                                   
                                                                ]

                             ];
  
    public function createManyConversation($data){
        $conversations = TableRegistry::get('Conversations');
        $newEntities = $conversations->newEntities($data);

        $patchEntities = $conversations->patchEntities($newEntities,$data);
        
        if ($conversations->saveMany($patchEntities)){
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
                        'user_id' => $data['user_id'],
                        'status' => $data['status']
                    ];
        $msgData =  [
                        'expertName' => $data['expertName'],
                        'serviceName' => $data['serviceName']
                    ];
        
        $conversations = TableRegistry::get('Conversations');
        $newEntity = $conversations->newEntity($reqData);
        
        if ($conversations->save($newEntity,['msgData' => $msgData])){
            Log::write('debug','Single Conversation has been saved ');
            Log::write('debug',$newEntity);
            return $newEntity;
        }else{
            throw new Exception("Error Processing Request while saving data.");
        }
        
    }

     private function __substitute($content, $hash){
       //write substitute logic
       $i=0;
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

    public function getConversationText($blockIdentifier,$user,$msgData = null){

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

    public function getNextBlock($blockIdentifier,$intent){

      $conversationResponses = self::$conversationArray[$blockIdentifier]['response'];
      
      foreach ($conversationResponses as $key => $value) {
          if(in_array($intent,$value['intent'])){
            $newBlockId =  (isset($value['block_identifier']))?$value['block_identifier']:null;
          }else{
            $newBlockId = null;
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

}
