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
              
              $customer = \Stripe\Customer::create([
                "description" => "Customer for sofia.moore@example.com",
                "source" => $stripeToken // obtained with Stripe.js
              ]);           
              
          } catch (Exception $e) {
              throw new Exception("User card could not be saved. Error via Stripe."); 
          }

        return ['stripe_customer_id' => $stripeCustomerId, 'stripe_card_id'=> $customer->default_source]; 

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

        return ['stripe_customer_id' => $response->customer, 'stripe_card_id'=> $response->id];

      }
      
    }

    public function deleteCard($userId){

	    if (!$this->request->is(['post', 'delete'])) {
	        throw new MethodNotAllowedException(__('BAD_REQUEST'));
	    }

	    $data = $this->request->getData();
      
  		if(!isset($data['stripe_card_id']) || !$data['stripe_card_id']){
  			throw new MethodNotAllowedException(__('BAD_REQUEST'));
  		}
  		
  		$userCards = TableRegistry::get('UserCards'); 
  		$getCardDetails = $userCards->findByUserId($userId)
  									              ->where(['stripe_card_id' => $data['stripe_card_id']])
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

  				$customer = \Stripe\Customer::retrieve($stripeCustomerId);
  				
  				$response = $customer->sources->retrieve($data['stripe_card_id'])->delete();              
                	if($response){

                		if (!$userCards->delete($getCardDetails)) {
  			            throw new Exception("User Card could not be deleted.");
  			        }else{
  			        	$status = true;
  			        }
                	}
  			} catch (Exception $e) {
  				
  			  throw new Exception("User card not deleted. Error in Stripe."); 
  			} 

		  return ['deleteCard' => $getCardDetails, 'status'=> $status];
    }

    public function listCards($userId){

    	if (!$this->request->is(['get'])) {
	        throw new MethodNotAllowedException(__('BAD_REQUEST'));
	    }

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
																		 ->all(array('limit'=>3, 'object' => 'card'));

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

    public function chargeCards($userId,$stripeCardId,$stripeCustomerId = null){

      if (!$this->request->is(['post'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      // pr($userId);
      // pr($stripeCardId);die;
      // pr($stripeCustomerId);
      // die;
      $data = $this->request->getData();
      
      if(!isset($stripeCardId) || !$stripeCardId){
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      if(!isset($stripeCustomerId) || !$stripeCustomerId){
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      // if(!isset($stripeCustomerId) || !$stripeCustomerId){

      //   $userCards = TableRegistry::get('UserCards'); 
      //   $getCardDetails = $userCards->findByUserId($userId)
      //                               ->first();
      //   $stripeCustomerId = null;   
        
      //   if($getCardDetails){
      //     $stripeCustomerId = $getCardDetails->stripe_customer_id;
      //   }

      // }
      // pr($stripeCustomerId);die;
      // if(!$stripeCustomerId || !isset($stripeCustomerId)){
      //   throw new NotFoundException(__("Stripe Customer Id doesn't found"));
      // }

      try {

        \Stripe\Stripe::setApiKey(Configure::read('StripeTestKey'));

        $customer = \Stripe\Charge::create(array(
                                                  "amount" => 2000,
                                                  "currency" => "usd",
                                                  "source" => $stripeCardId, // card-id,
                                                  "customer" => $stripeCustomerId,
                                                  "description" => "Charge for jacob.smith@example.com"
                                                ));
        
        if($customer){
          $customerChargeDetails = $customer->jsonSerialize();
        }
         
      } catch (Exception $e) {
        
        throw new Exception("User card not charged. Error in Stripe."); 
      } 

      return [ 'status' => true,'data' => $customerChargeDetails];
        
    }


}
