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

    public function addCard($userId){
      
      if (!$this->request->is(['post'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      
      $data = $this->request->getData();
      
      if(!isset($data['stripeJsToken']) || !$data['stripeJsToken']){
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      
      \Stripe\Stripe::setApiKey(Configure::read('StripeTestKey'));

      $model = TableRegistry::get('Users');
      
      $users = $model->findById($userId)
                     ->contain(['Experts','UserCards'])
                     ->first();
      
      $userCards = TableRegistry::get('UserCards'); 

      if(!isset($users->user_cards[0])){
      	
        //when the user is NOT registered on stripe.
        
        try {
              
              $customer = \Stripe\Customer::create([
                "description" => "Customer for sofia.moore@example.com",
                "source" => $data['stripeJsToken'] // obtained with Stripe.js
              ]);
              $userCard = [
              				  'user_id' => $userId,
                              'expert_id' => $users->experts[0]->id,
                              'stripe_customer_id' => $customer->id,
                              'stripe_card_id' => $customer->default_source,
                              'status' => 1
                            ];

              
              $newCard = $userCards->newEntity($userCard);
              if($userCards->save($newCard)){
                $status = true;
              }else{
                throw new Exception("User card could not be saved. Error while saving the data.");
              }
              
              
          } catch (Exception $e) {
              throw new Exception("User card could not be saved. Error via Stripe."); 
          }  
      
      }else{
      	
        //when the user is already registered on stripe.
        $stripeCusId = $users->user_cards[0]->stripe_customer_id;
        
        try {
              
            $customer = \Stripe\Customer::retrieve($stripeCusId);
            
            $customer->sources->create(["source" => $data['stripeJsToken']]);
            $response = json_decode($customer->sources->getlastResponse()->body);
            $userCard = [
            				'user_id'=> $userId,
                            'expert_id' => $users->experts[0]->id,
                            'stripe_customer_id' => $response->customer,
                            'stripe_card_id' => $response->id,
                            'status' => 1
                          ];

            $newCard = $userCards->newEntity($userCard);
            
            if($userCards->save($newCard)){
              $status = true;
            }else{
              throw new Exception("User card could not be saved. Error while saving the data.");
            }
            
          } catch (Exception $e) {
              throw new Exception("User card could not be saved. Error via Stripe.");
          }
      
      }
      return ['newCard' => $newCard, 'status'=> $status];
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

    public function chargeCards($userId){

      if (!$this->request->is(['post'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      
      $data = $this->request->getData();
      
      if(!isset($data['stripe_card_id']) || !$data['stripe_card_id']){
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

        $customer = \Stripe\Charge::create(array(
                                                  "amount" => 2000,
                                                  "currency" => "usd",
                                                  "source" => $data['stripe_card_id'], // card-id,
                                                  "customer" => $stripeCustomerId,
                                                  "description" => "Charge for jacob.smith@example.com"
                                                ));
        
        if($customer){
            $customerChargeDetails = $customer->jsonSerialize();
            pr($customerChargeDetails);die;
            
        }
         
      } catch (Exception $e) {
        
        throw new Exception("User card not charged. Error in Stripe."); 
      } 

      return ['data' => $reqData, 'status' => true];
        
    }


}
