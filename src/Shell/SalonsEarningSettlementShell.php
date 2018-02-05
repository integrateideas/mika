<?php
namespace App\Shell;

use Cake\Log\Log;
use Cake\Console\Shell;
use Cake\Collection\Collection;
use Cake\Controller\Controller;
use Cake\Network\Exception\NotFoundException;

/**
 * SalonsEarningSettlement shell command.
 */
class SalonsEarningSettlementShell extends Shell
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

    public function expertWeeklyEarning(){
    
        $this->loadModel('Appointments');
        $appointments = $this->Appointments->find()
                                           ->where([
                                                        'is_completed' => 1,
                                                        'earning_settlement' => false
                                                    ])
                                           ->contain(['Experts.UserSalons.AccountDetails','Transactions'])
                                           ->all()
                                           ->toArray();

        if(!empty($appointments)){
            $payoutAmount = [];
            foreach ($appointments as $appointment) {
                if((isset($appointment->expert) && $appointment->expert) && (isset($appointment->transaction) && $appointment->transaction)){
                    if(isset($payoutAmount[$appointment->expert->user_salon_id])){
                        $payoutAmount[$appointment->expert->user_salon_id]['amount'] += $appointment->transaction->transaction_amount;
                    }else{
                        $payoutAmount[$appointment->expert->user_salon_id]['amount'] = $appointment->transaction->transaction_amount;
                        $payoutAmount[$appointment->expert->user_salon_id]['stripe_bank_account_id'] = $appointment->expert->user_salon->account_details[0]->stripe_bank_account_id;
                    }
                }
            }
            if(!empty($payoutAmount)){
                $chargeCaptured = [];
                foreach ($payoutAmount as $reqData) {
                    
                    $controller = new Controller();
                    $stripeComponent = $controller->loadComponent('Stripe');
                    $chargeCaptured = $stripeComponent->payout($reqData['stripe_bank_account_id'],
                                                               $reqData['amount']);
                    
                    $this->loadModel('SalonPayouts');
                    $salonPayouts = $this->SalonPayouts->newEntity();

                }
                pr($chargeCaptured);die;

            }
            
        }

    }
}
