<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * AppointmentReviews Controller
 *
 * @property \App\Model\Table\AppointmentReviewsTable $AppointmentReviews
 *
 * @method \App\Model\Entity\AppointmentReview[] paginate($object = null, array $settings = [])
 */
class AppointmentReviewsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Experts', 'Users', 'Appointments']
        ];
        $appointmentReviews = $this->paginate($this->AppointmentReviews);

        $this->set(compact('appointmentReviews'));
        $this->set('_serialize', ['appointmentReviews']);
    }

    /**
     * View method
     *
     * @param string|null $id Appointment Review id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $appointmentReview = $this->AppointmentReviews->get($id, [
            'contain' => ['Experts', 'Users', 'Appointments']
        ]);

        $this->set('appointmentReview', $appointmentReview);
        $this->set('_serialize', ['appointmentReview']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $appointmentReview = $this->AppointmentReviews->newEntity();
        if ($this->request->is('post')) {
            $appointmentReview = $this->AppointmentReviews->patchEntity($appointmentReview, $this->request->getData());
            if ($this->AppointmentReviews->save($appointmentReview)) {
                $this->Flash->success(__('The appointment review has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The appointment review could not be saved. Please, try again.'));
        }
        $experts = $this->AppointmentReviews->Experts->find('list', ['limit' => 200]);
        $users = $this->AppointmentReviews->Users->find('list', ['limit' => 200]);
        $appointments = $this->AppointmentReviews->Appointments->find('list', ['limit' => 200]);
        $this->set(compact('appointmentReview', 'experts', 'users', 'appointments'));
        $this->set('_serialize', ['appointmentReview']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Appointment Review id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $appointmentReview = $this->AppointmentReviews->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $appointmentReview = $this->AppointmentReviews->patchEntity($appointmentReview, $this->request->getData());
            if ($this->AppointmentReviews->save($appointmentReview)) {
                $this->Flash->success(__('The appointment review has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The appointment review could not be saved. Please, try again.'));
        }
        $experts = $this->AppointmentReviews->Experts->find('list', ['limit' => 200]);
        $users = $this->AppointmentReviews->Users->find('list', ['limit' => 200]);
        $appointments = $this->AppointmentReviews->Appointments->find('list', ['limit' => 200]);
        $this->set(compact('appointmentReview', 'experts', 'users', 'appointments'));
        $this->set('_serialize', ['appointmentReview']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Appointment Review id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $appointmentReview = $this->AppointmentReviews->get($id);
        if ($this->AppointmentReviews->delete($appointmentReview)) {
            $this->Flash->success(__('The appointment review has been deleted.'));
        } else {
            $this->Flash->error(__('The appointment review could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
