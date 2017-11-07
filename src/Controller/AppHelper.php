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
                                                                "text"=>"Hi, <<expert name>>, Good morning. Would you like to make yourself available on Mika today? Ignore this text to cancel.", 
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

                                    "Availability_updated" => [
                                                                "text"=>"Hi, <<expert name>>, good morning", 
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
                                    "Availability_not_updated" => [
                                                                    "text"=>"Hi, <<expert name>>, good morning", 
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

   public function getConversationText($blockIdentifier){

      if(isset(self::$conversationArray[$blockIdentifier])){
        if(isset(self::$conversationArray[$blockIdentifier]['text'])){
          return self::$conversationArray[$blockIdentifier]['text'];
        }else{
          die('block k paas text ni hai');  
        }
      } else{
        die('bhai galat value hai/');
      }   
    }

    public function getNextBlock($blockIdentifier,$intent){
      
      if(isset(self::$conversationArray[$blockIdentifier])){
        $response = self::$conversationArray[$blockIdentifier]['response'];
        pr($response);die;
        //compare intent with define intents
        //return block identifier of the intent which is matched
      } else{
        die('bhai galat value hai/');
      }   
    }

}
