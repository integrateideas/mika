<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * AppointmentTransactions Controller
 *
 * @property \App\Model\Table\AppointmentTransactionsTable $AppointmentTransactions
 *
 * @method \App\Model\Entity\AppointmentTransaction[] paginate($object = null, array $settings = [])
 */
class AppointmentTransactionsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Appointments', 'Charges']
        ];
        $appointmentTransactions = $this->paginate($this->AppointmentTransactions);

        $this->set(compact('appointmentTransactions'));
        $this->set('_serialize', ['appointmentTransactions']);
    }

    /**
     * View method
     *
     * @param string|null $id Appointment Transaction id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $appointmentTransaction = $this->AppointmentTransactions->get($id, [
            'contain' => ['Appointments', 'Charges']
        ]);

        $this->set('appointmentTransaction', $appointmentTransaction);
        $this->set('_serialize', ['appointmentTransaction']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $appointmentTransaction = $this->AppointmentTransactions->newEntity();
        if ($this->request->is('post')) {
            $appointmentTransaction = $this->AppointmentTransactions->patchEntity($appointmentTransaction, $this->request->getData());
            if ($this->AppointmentTransactions->save($appointmentTransaction)) {
                $this->Flash->success(__('The appointment transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The appointment transaction could not be saved. Please, try again.'));
        }
        $appointments = $this->AppointmentTransactions->Appointments->find('list', ['limit' => 200]);
        $charges = $this->AppointmentTransactions->Charges->find('list', ['limit' => 200]);
        $this->set(compact('appointmentTransaction', 'appointments', 'charges'));
        $this->set('_serialize', ['appointmentTransaction']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Appointment Transaction id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $appointmentTransaction = $this->AppointmentTransactions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $appointmentTransaction = $this->AppointmentTransactions->patchEntity($appointmentTransaction, $this->request->getData());
            if ($this->AppointmentTransactions->save($appointmentTransaction)) {
                $this->Flash->success(__('The appointment transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The appointment transaction could not be saved. Please, try again.'));
        }
        $appointments = $this->AppointmentTransactions->Appointments->find('list', ['limit' => 200]);
        $charges = $this->AppointmentTransactions->Charges->find('list', ['limit' => 200]);
        $this->set(compact('appointmentTransaction', 'appointments', 'charges'));
        $this->set('_serialize', ['appointmentTransaction']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Appointment Transaction id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $appointmentTransaction = $this->AppointmentTransactions->get($id);
        if ($this->AppointmentTransactions->delete($appointmentTransaction)) {
            $this->Flash->success(__('The appointment transaction has been deleted.'));
        } else {
            $this->Flash->error(__('The appointment transaction could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
