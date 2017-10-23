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
        $this->paginate = [
            'contain' => ['Users', 'Experts']
        ];
        $userFavouriteExperts = $this->paginate($this->UserFavouriteExperts);

        $this->set(compact('userFavouriteExperts'));
        $this->set('_serialize', ['userFavouriteExperts']);
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
        $userFavouriteExpert = $this->UserFavouriteExperts->get($id, [
            'contain' => ['Users', 'Experts']
        ]);

        $this->set('userFavouriteExpert', $userFavouriteExpert);
        $this->set('_serialize', ['userFavouriteExpert']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        pr('here');die;
        $userFavouriteExpert = $this->UserFavouriteExperts->newEntity();
        if ($this->request->is('post')) {
            $userFavouriteExpert = $this->UserFavouriteExperts->patchEntity($userFavouriteExpert, $this->request->getData());
            if ($this->UserFavouriteExperts->save($userFavouriteExpert)) {
                $this->Flash->success(__('The user favourite expert has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user favourite expert could not be saved. Please, try again.'));
        }
        $users = $this->UserFavouriteExperts->Users->find('list', ['limit' => 200]);
        $experts = $this->UserFavouriteExperts->Experts->find('list', ['limit' => 200]);
        $this->set(compact('userFavouriteExpert', 'users', 'experts'));
        $this->set('_serialize', ['userFavouriteExpert']);
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
        $userFavouriteExpert = $this->UserFavouriteExperts->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $userFavouriteExpert = $this->UserFavouriteExperts->patchEntity($userFavouriteExpert, $this->request->getData());
            if ($this->UserFavouriteExperts->save($userFavouriteExpert)) {
                $this->Flash->success(__('The user favourite expert has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user favourite expert could not be saved. Please, try again.'));
        }
        $users = $this->UserFavouriteExperts->Users->find('list', ['limit' => 200]);
        $experts = $this->UserFavouriteExperts->Experts->find('list', ['limit' => 200]);
        $this->set(compact('userFavouriteExpert', 'users', 'experts'));
        $this->set('_serialize', ['userFavouriteExpert']);
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
        $this->request->allowMethod(['post', 'delete']);
        $userFavouriteExpert = $this->UserFavouriteExperts->get($id);
        if ($this->UserFavouriteExperts->delete($userFavouriteExpert)) {
            $this->Flash->success(__('The user favourite expert has been deleted.'));
        } else {
            $this->Flash->error(__('The user favourite expert could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
