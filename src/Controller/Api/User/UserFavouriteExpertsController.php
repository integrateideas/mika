<?php
namespace App\Controller\Api\User;

use App\Controller\Api\User\ApiController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Core\Exception\Exception;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Auth\DefaultPasswordHasher;
use Firebase\JWT\JWT;
use Cake\Utility\Security;
use Cake\I18n\Time;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\Collection\Collection;

/**
 * UserFavouriteExperts Controller
 *
 * @property \App\Model\Table\UserFavouriteExpertsTable $UserFavouriteExperts
 *
 * @method \App\Model\Entity\UserFavouriteExpert[] paginate($object = null, array $settings = [])
 */
class UserFavouriteExpertsController extends ApiController
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
        
        $userFavouriteExperts = $this->UserFavouriteExperts->find()->contain(['Users', 'Experts'])->all();

        $success = true;

        $this->set(compact('userFavouriteExperts','success'));
        $this->set('_serialize', ['userFavouriteExperts','success']);
    }

    /**
     * View method
     *
     * @param string|null $id User Favourite Expert id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $userFavouriteExpert = $this->UserFavouriteExperts->get($id, [
            'contain' => ['Users', 'Experts']
        ]);

        $success = true;

        $this->set(compact('userFavouriteExpert','success'));
        $this->set('_serialize', ['userFavouriteExpert','success']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if(!$this->request->is(['post'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $data = [
                    'user_id' => $this->Auth->user('id'),
                    'expert_id' => $this->request->data['expert_id']
                ];
        $userFavouriteExpert = $this->UserFavouriteExperts->newEntity();
        $userFavouriteExpert = $this->UserFavouriteExperts->patchEntity($userFavouriteExpert, $data);

        if (!$this->UserFavouriteExperts->save($userFavouriteExpert)) {
          
          if($userFavouriteExpert->errors()){
            $this->_sendErrorResponse($userFavouriteExpert->errors());
          }
          throw new Exception("Error Processing Request");
        }
        
        $success = true;
        
        $this->set(compact('userFavouriteExpert','success'));
        $this->set('_serialize', ['userFavouriteExpert','success']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User Favourite Expert id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {

        if(!$this->request->is(['patch', 'post', 'put'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        $userFavouriteExpert = $this->UserFavouriteExperts->get($id, [
            'contain' => []
        ]);

        $userFavouriteExpert = $this->UserFavouriteExperts->patchEntity($userFavouriteExpert, $this->request->getData());

            if (!$this->UserFavouriteExperts->save($userFavouriteExpert)) {
              
              if($userFavouriteExpert->errors()){
                $this->_sendErrorResponse($userFavouriteExpert->errors());
              }
              throw new Exception("Error Processing Request");
            }
        
        $success = true;
        
        $this->set(compact('userFavouriteExpert','success'));
        $this->set('_serialize', ['userFavouriteExpert','success']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User Favourite Expert id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if (!$this->request->is(['patch', 'post', 'put','delete'])) {
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $userFavouriteExpert = $this->UserFavouriteExperts->get($id);

        if (!$this->UserFavouriteExperts->delete($userFavouriteExpert)) {
            throw new Exception("Expert specialization service could not be deleted.");
        }

        $success = true;
        
        $this->set(compact('success'));
        $this->set('_serialize', ['success']);
    }
}
