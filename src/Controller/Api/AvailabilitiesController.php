<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\Exception;
use Cake\I18n\Date;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Stripe\Stripe;

/**
 * Availabilities Controller
 *
 * @property \App\Model\Table\AvailabilitiesTable $Availabilities
 *
 * @method \App\Model\Entity\Availability[] paginate($object = null, array $settings = [])
 */
class AvailabilitiesController extends ApiController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $availabilities = $this->Availabilities->find()
                                                ->contain(['Experts'])
                                                ->all();

        $this->set(compact('availabilities'));
        $this->set('_serialize', ['availabilities']);
    }

    //TODO: add time constraints
    public function activeAvailabilities()
    {
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $availabilities = $this->Availabilities->find()
                                                ->contain(['Experts'])
                                                ->where(['status' => 1])
                                                ->all();

        $this->set(compact('availabilities'));
        $this->set('_serialize', ['availabilities']);
    }

    /**
     * View method
     *
     * @param string|null $id Availability id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $availability = $this->Availabilities->get($id, [
            'contain' => ['Experts']
        ]);

        $this->set('availability', $availability);
        $this->set('_serialize', ['availability']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {   


        $conn = ConnectionManager::get('default');
        $conn->driver()->autoQuoting(true);
        if (!$this->request->is(['post'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $this->request->data['available_from'] =  new \DateTime($this->request->data['available_from']);
        $this->request->data['available_to']  = new \DateTime($this->request->data['available_to']);
        
        // $this->request->data['available_from'] = date('Y-m-d H:i:s');
        // $this->request->data['available_to'] = date('Y-m-d H:i:s');

        $availability = $this->Availabilities->newEntity();
        
        $availability = $this->Availabilities->patchEntity($availability, $this->request->getData());
        if (!$this->Availabilities->save($availability)) {
            throw new Exception("Availability could not be saved.");
        }
        
        $success = true;

        $this->set(compact('availability', 'success'));
        $this->set('_serialize', ['availability','success']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Availability id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if (!$this->request->is(['patch', 'post', 'put'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $availability = $this->Availabilities->get($id, [
            'contain' => []
        ]);
        
        if(isset($this->request->data['available_from']) && !empty($this->request->data['available_from'])){
          $this->request->data['available_from'] =  new \DateTime($this->request->data['available_from']); 
        }

        if(isset($this->request->data['available_to']) && !empty($this->request->data['available_to'])){
          $this->request->data['available_to'] =  new \DateTime($this->request->data['available_to']); 
        }

        $availability = $this->Availabilities->patchEntity($availability, $this->request->getData());
        if (!$this->Availabilities->save($availability)) {
            throw new Exception("Availability could not be edited.");
        }
        
        $success = true;
        
        $this->set(compact('availability', 'success'));
        $this->set('_serialize', ['availability','success']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Availability id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if (!$this->request->is(['patch', 'post', 'put','delete'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $availability = $this->Availabilities->get($id);
        
        if (!$this->Availabilities->delete($availability)) {
            throw new Exception("Availability could not be deleted.");
        } 
        
        $success = true;
        
        $this->set(compact('success'));
        $this->set('_serialize', ['success']);
    }

    public function testStripe()
    {
        $this->loadModel('ExpertCards');
        $expertCards = $this->ExpertCards->findByExpertId(6)
                                        ->first();

// pr($expertCards); die;
        \Stripe\Stripe::setApiKey(Configure::read('StripeTestKey'));
        
        $sampleToken = 'tok_1B6IZnDIf2kSEMB3iBBoelxp';

        try {
            
            $return = \Stripe\Customer::create(array(
              "description" => "Customer for sofia.moore@example.com",
              "source" => "tok_visa" // obtained with Stripe.js
            ));

            // $customer = \Stripe\Customer::retrieve("cus_BTYVHxZAFCSB6s");
            // $customer->sources->all(['limit'=>3, 'hw_objrec2array(object_record)ect' => 'card']);
            
            // $charge = \Stripe\Charge::create(array(
            //               "amount" => 1600000,
            //               "currency" => "usd",
            //               "customer" => $expertCards['stripe_customer_id'],
            //               "source" => $expertCards['stripe_card_id'], // "card_1B6IZnDIf2kSEMB3NSaxI0iP"
            //               "description" => "First test Charge for sofia.moore@example.com"
            //             ));

            // $plan = \Stripe\Plan::create(array(
            //                                   "amount" => 321,
            //                                   "interval" => "day",
            //                                   "name" => "Silver plan",
            //                                   "currency" => "usd",
            //                                   "id" => "silver-plan")
            //                                 );

            $subscription = \Stripe\Subscription::create([
                                                          "customer" => "cus_BTYVHxZAFCSB6s",
                                                          "items" => [["plan" => "silver-plan"]]
                                                        ]);
                        pr($subscription); die;
        } catch (Exception $e) {
            pr($e); die;
        }

        pr($return); die;

        // $createCard = $http->post($host+'\Stripe\Stripe')::setApiKey("pk_test_0Q6dnCgnIwyVHUdwHGVrwYnU");
    }
}
