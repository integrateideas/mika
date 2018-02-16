<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Stripe\Stripe;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Core\Exception\Exception;
use Cake\Network\Exception\NotFoundException;
use Cake\Log\Log;

/**
 * Stripe component
 */
class StripeComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function addCard($userId,$stripeToken){
      \Stripe\Stripe::setApiKey(Configure::read('StripeTestKey'));

      $model = TableRegistry::get('Users');
      
      $users = $model->findById($userId)
                     ->contain(['UserCards'])
                     ->first();
      
      $userCards = TableRegistry::get('UserCards'); 

      if(!isset($users->user_cards[0])){
        //when the user is NOT registered on stripe.  
        try {
              // pr($stripeToken);die;
              $customer = \Stripe\Customer::create([
                "description" => "Customer for sofia.moore@example.com",
                "source" => $stripeToken // obtained with Stripe.js
              ]);    
              $response = json_decode($customer->__toJSON());
              $response = $response->sources->data[0];   
              
          } catch (Exception $e) {
              throw new Exception("User card could not be saved. Error via Stripe."); 
          }

      }else{
      	
        //when the user is already registered on stripe.
        $stripeCusId = $users->user_cards[0]->stripe_customer_id;
        
        try {
              
            $customer = \Stripe\Customer::retrieve($stripeCusId);
            
            $customer->sources->create(["source" => $stripeToken]);
            $response = json_decode($customer->sources->getlastResponse()->body);
               
          } catch (Exception $e) {
              throw new Exception("User card could not be saved. Error via Stripe.");
          }

      }
      return ['stripe_data' => $response];       
    }

    public function viewCard($stripeCustomerId,$stripeCardId){
      
      \Stripe\Stripe::setApiKey(Configure::read('StripeTestKey'));

    
        try {
  
              $customer = \Stripe\Customer::retrieve($stripeCustomerId);
              $response = $customer->sources->retrieve($stripeCardId);
              $response = json_decode($customer->__toJSON());
              $response = $response->sources->data[0];   
              
          } catch (Exception $e) {
              throw new Exception("User card could not be saved. Error via Stripe."); 
          }

      return ['viewCard' => $response];       
    }

    public function deleteCard($stripeCardId,$stripeCustomerId){
      
  		if(!isset($stripeCardId) || !$stripeCardId){
  			throw new MethodNotAllowedException(__('Missing_Field',"Stripe card id is missing"));
  		}

  		if(!isset($stripeCustomerId) || !$stripeCustomerId){
        throw new MethodNotAllowedException(__('Missing_Field',"Stripe customer id is missing"));
      }

  		try {

  				\Stripe\Stripe::setApiKey(Configure::read('StripeTestKey'));

  				$customer = \Stripe\Customer::retrieve($stripeCustomerId);
  				
  				$response = $customer->sources->retrieve($stripeCardId)->delete();              
      
  			} catch (Exception $e) {
  				
  			  throw new Exception("User card not deleted. Error in Stripe."); 
  			} 

		  return ['deleteCard' => $response];
    }

    public function listCards($userId){
	    $userCards = TableRegistry::get('UserCards'); 
  		$getCardDetails = $userCards->findByUserId($userId)
  									              ->first();

  		$stripeCustomerId = null;		
  		
  		if($getCardDetails){
  			$stripeCustomerId = $getCardDetails->stripe_customer_id;
  		}		
  		if(!$stripeCustomerId || !isset($stripeCustomerId)){
  			throw new NotFoundException(__("Stripe Customer Id doesn't found"));
  		}

	    try {

				\Stripe\Stripe::setApiKey(Configure::read('StripeTestKey'));

				$customer = \Stripe\Customer::retrieve($stripeCustomerId)->sources
																		 ->all(array('limit'=>5, 'object' => 'card'));

				$reqData = [];

        if(!$customer){
          throw new NotFoundException(__("No List Exists."));
        }
				foreach ($customer->offsetGet('data') as $key => $value) {
				    $reqData[] = $value->jsonSerialize(); 
				}
         
      } catch (Exception $e) {
        
        throw new Exception("User card not deleted. Error in Stripe."); 
      } 

      return ['data' => $reqData, 'status' => true];
    }

    public function chargeCards($serviceAmount,$stripeCardId,$stripeCustomerId,$serviceName = null,$userName = null){
      
      if(!isset($serviceAmount) || !$serviceAmount){
        throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING',"Amount for the service"));
      }
      if(!isset($stripeCardId) || !$stripeCardId){
        throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING',"Stripe card id"));
      }
      if(!isset($stripeCustomerId) || !$stripeCustomerId){
        throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING',"Stripe customer id"));
      }

      try {

        \Stripe\Stripe::setApiKey(Configure::read('StripeTestKey'));

        $customer = \Stripe\Charge::create(array(
                                                  "amount" => ($serviceAmount*100),
                                                  "currency" => "usd",
                                                  "source" => $stripeCardId,
                                                  "customer" => $stripeCustomerId,
                                                  "description" => "Charge for ".$serviceName." by ".$userName,
                                                  "capture" => false
                                                ));
        
        if($customer){
          $customerChargeDetails = $customer->jsonSerialize();
        }
         
      } catch (Exception $e) {
        
        throw new Exception("User card not charged. Error in Stripe."); 
      } 

      return [ 'status' => true,'data' => $customerChargeDetails];
        
    }

    public function createBankAccountToken($accountHolderName, $accountNumber, $routingNumber, $accountHolderType){

      if(!isset($accountHolderName) || !$accountHolderName){
        throw new BadRequestException("Missing Account Holder Name");
      }
      if(!isset($accountNumber) || !$accountNumber){
        throw new BadRequestException("Missing Account Number");
      }
      if(!isset($routingNumber) || !$routingNumber){
        throw new BadRequestException("Missing Routing Number");
      }
      if(!isset($accountHolderType) || !$accountHolderType){
        throw new BadRequestException("Missing Account Holder Type");
      }

      try {

        \Stripe\Stripe::setApiKey(Configure::read('StripeTestKey'));

        $customerStripeBankAccountToken = \Stripe\Token::create(array(
                                            "bank_account" => array(
                                              "country" => "US",
                                              "currency" => "usd",
                                              "account_holder_name" => $accountHolderName,
                                              "account_holder_type" => $accountHolderType,
                                              "routing_number" => $routingNumber,
                                              "account_number" => $accountNumber
                                            )
                                        ));
        
        if($customerStripeBankAccountToken){
          $token = $customerStripeBankAccountToken->jsonSerialize();
          return $token;
        }
         
      } catch (Exception $e) {

        throw new Exception("Bank Account Token has not been created. Error in Stripe."); 
      }

    }

    public function createBankAccount($stripeCustomerId, $stripeBankAccountToken){
      
      if(!isset($stripeCustomerId) || !$stripeCustomerId){
        throw new BadRequestException("Missing Stripe Customer Id");
      }
      if(!isset($stripeBankAccountToken) || !$stripeBankAccountToken){
        throw new BadRequestException("Missing Stripe Bank Account Token");
      }

      try {

        \Stripe\Stripe::setApiKey(Configure::read('StripeTestKey'));

        $customer = \Stripe\Customer::retrieve($stripeCustomerId);
        $bankAccount = $customer->sources->create(array("source" => $stripeBankAccountToken));
        
        if($bankAccount){
          $bankAccount = $bankAccount->jsonSerialize();
          return $bankAccount;
        }
         
      } catch (Exception $e) {

        throw new Exception("Bank Account has not been created. Error in Stripe."); 
      }
    }

    public function RetrieveAccountDetails($stripeUserAccountId){
      try {

        \Stripe\Stripe::setApiKey(Configure::read('StripeTestKey'));

        $retrieveBankDetails = \Stripe\Account::retrieve($stripeUserAccountId);
        if($retrieveBankDetails){
          $retrieveBankDetails = $retrieveBankDetails->jsonSerialize();
          return $retrieveBankDetails;
        }
         
      } catch (Exception $e) {

        throw new Exception("Bank Account has not been created. Error in Stripe."); 
      }
    }

    public function ConnectAccount($code){
      $clientId = "ca_CFZnHhzQjSxDc2fURkjTGVgeZF3StF5c"; // for testing only
      try {

        $token_request_body = array(
                                      'client_secret' => Configure::read('StripeTestKey'),
                                      'grant_type' => 'authorization_code',
                                      'client_id' => $clientId,
                                      'code' => $code
                                    );
        
        $req = curl_init("https://connect.stripe.com/oauth/token");
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($req, CURLOPT_POST, true );
        curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
        $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
        $resp = json_decode(curl_exec($req), true);
        curl_close($req);
        if(isset($resp['access_token'])){
          return $resp;
        }elseif($resp['error']){
          return $resp['error'];
        }else{
              $authorize_request_body = array(
                                                'response_type' => $code,
                                                'client_id' => $clientId
                                              );
              $url = AUTHORIZE_URI . '?' . http_build_query($authorize_request_body);
               echo "<a href='$url'>Connect with Stripe</a>";
        }

         
      } catch (Exception $e) {

        throw new Exception("Bank Account has not been connected. Error in Stripe."); 
      }
    }

    public function payout($destinationAccountId,$payoutAmount){
          
          if(!isset($destinationAccountId) || !$destinationAccountId){
            throw new BadRequestException("Missing Stripe Bank Account Id");
          }
          if(!isset($payoutAmount) || !$payoutAmount){
            throw new BadRequestException("Missing Payout Amount");
          }
          
          try {

              \Stripe\Stripe::setApiKey(Configure::read('StripeTestKey'));

              $transfer = \Stripe\Transfer::create(array(
                                                          "amount" => $payoutAmount,
                                                          "currency" => "usd",
                                                          "destination" => $destinationAccountId,
                                                        ));
              
              if($transfer){
                $transfer = $transfer->jsonSerialize();
                return $transfer;
              }
               
            } catch (Exception $e) {

              throw new Exception("Payout has not been completed. Error in Stripe."); 
            }

    }

    public function captureCharge($stripeChargeId){

      if(!isset($stripeChargeId) || !$stripeChargeId){
        throw new BadRequestException("Missing Stripe Charge Id");
      }
      try {

        \Stripe\Stripe::setApiKey(Configure::read('StripeTestKey'));

        $charge = \Stripe\Charge::retrieve($stripeChargeId);
        $charge = $charge->capture();
        if($charge){
          $paymentChargeCaptured = $charge->jsonSerialize();
          return $paymentChargeCaptured;
        }
         
      } catch (Exception $e) {

        throw new Exception("Charge has not been captured. Error in Stripe."); 
      }
    }


}
