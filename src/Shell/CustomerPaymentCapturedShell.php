<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\I18n\FrozenTime;
use Cake\Controller\Controller;
use Cake\Collection\Collection;

/**
 * CustomerPaymentCaptured shell command.
 */
class CustomerPaymentCapturedShell extends Shell
{

    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     */
    public function main()
    {
        $this->out($this->OptionParser->help());
    }

    public function capturedPayment(){
        
        $date = new FrozenTime('today');
        // $startdate = $date->modify('00:00:00');
        $enddate = $date->modify('23:55:00');

        $this->loadModel('Appointments');
        $appointments = $this->Appointments->find()
                                           ->where(['is_completed' => 1])
                                           ->where(function ($exp) use ($enddate) {
                                              return $exp
                                                ->lte('Appointments.created', $enddate);
                                            })
                                           ->contain(['Transactions' => function ($q){
                                                return $q->where(['payment_captured' => 0]);
                                           }])
                                           ->all()
                                           ->toArray();
        
        if(!empty($appointments)){
            $chargeCaptured = [];
            foreach ($appointments as $appointment) {
                if(isset($appointment->transaction) && $appointment->transaction){
                    $controller = new Controller();
                    $stripeComponent = $controller->loadComponent('Stripe');
                    $chargeCaptured = $stripeComponent->captureCharge($appointment->transaction
                                                                                    ->stripe_charge_id);        
                    if($chargeCaptured['paid'] == 1){
                        $reqData[] = $chargeCaptured;
                    }
                }
            }

            if(!empty($reqData)){

                $stripeChargeIds = (new Collection($reqData))->extract('id')->toArray();
            
                $this->loadModel('Transactions');
                $updateTransactionStatus = $this->Transactions->updateAll(
                                                                ['payment_captured' => true], // fields
                                                                ['stripe_charge_id IN' => $stripeChargeIds] // conditions
                                                            );
                $this->out($updateTransactionStatus);
                $this->out("Payment has been captured successfully.");
            }
            
            $this->out("No Payment has been captured.");
        }
    }
}
