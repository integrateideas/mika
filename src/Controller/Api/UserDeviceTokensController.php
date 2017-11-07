<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Core\Exception\Exception;
//use Cake\Exception\InternalServer

/**
 * UserDeviceTokens Controller
 *
 * @property \App\Model\Table\UserDeviceTokensTable $UserDeviceTokens
 *
 * @method \App\Model\Entity\UserDeviceToken[] paginate($object = null, array $settings = [])
 */
class UserDeviceTokensController extends ApiController
{
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $userDeviceTokenRequest['user_id'] = $this->Auth->user('id') ; //obtain from auth
        $userDeviceTokenRequest['device_token'] = $this->request->getData('device_token'); //obtain from request

        if(!$userDeviceTokenRequest['device_token']) {
            throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING','device_token'));
        }

        $userDeviceToken = $this->UserDeviceTokens->newEntity();
        $userDeviceToken = $this->UserDeviceTokens->patchEntity($userDeviceToken, $userDeviceTokenRequest);
        if (!$this->UserDeviceTokens->save($userDeviceToken)) {
            throw new Exception("Error adding device token", 1); 
        } 

        $this->set('data', $userDeviceToken);
        $this->set('status', true);
        $this->set('_serialize', ['status','data']);
    }


    /**
     * Delete method
     *
     * @param string|null $id User Device Token id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete()
    {

        $this->request->allowMethod(['post', 'delete']);
        $token = $this->request->getData('device_token');
        
        if(!$token) {
            throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING','device_token'));
        }

        $userDeviceToken = $this->UserDeviceTokens->findByDeviceToken($token)->first();
        if (!$this->UserDeviceTokens->delete($userDeviceToken)) {
            throw new Exception("Error deleting token");
        }

        $this->set('status',true);
        $this->set('_serialize', ['status']);   
    }
}