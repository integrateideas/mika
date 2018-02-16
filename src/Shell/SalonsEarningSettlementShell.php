<?php
namespace App\Shell;

use Cake\Log\Log;
use Cake\Console\Shell;
use Cake\Collection\Collection;
use Cake\Controller\Controller;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

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
                                           ->contain(['Experts.UserSalons.ConnectSalonAccounts','Transactions'])
                                           ->all()
                                           ->toArray();

        if(!empty($appointments)){
            $payoutAmount = [];
            foreach ($appointments as $appointment) {
                if((isset($appointment->expert) && $appointment->expert) && (isset($appointment->transaction) && $appointment->transaction)){
                    if(isset($payoutAmount[$appointment->expert->user_salon_id])){
                        $payoutAmount[$appointment->expert->user_salon_id]['amount'] += $appointment->transaction->transaction_amount;
                        $payoutAmount[$appointment->expert->user_salon_id]['appointments'][] = $appointment->id;
                    }else{
                        $payoutAmount[$appointment->expert->user_salon_id]['amount'] = $appointment->transaction->transaction_amount;
                        $payoutAmount[$appointment->expert->user_salon_id]['appointments'][] = $appointment->id;
                        $payoutAmount[$appointment->expert->user_salon_id]['stripe_bank_account_id'] = $appointment->expert->user_salon->connect_salon_accounts[0]->stripe_user_account_id;
                    }
                }
            }

            if(!empty($payoutAmount)){
                $salonPayoutModel = TableRegistry::get('SalonPayouts');
                    
                    $amountSettled = [];
                    foreach ($payoutAmount as $payoutData) {

                        $controller = new Controller();
                        $stripeComponent = $controller->loadComponent('Stripe');
                        $amountSettled = $stripeComponent->payout(
                                                                    $payoutData['stripe_bank_account_id'],
                                                                    $payoutData['amount']
                                                                  );
                        Log::write('debug', $amountSettled);
                        $this->loadModel('ConnectSalonAccounts');
                        $connectSalonAccountData = $this->ConnectSalonAccounts->findByStripeUserAccountId($amountSettled['destination'])->first();
                        if($connectSalonAccountData){   
                            Log::write('debug', $connectSalonAccountData);
                            $data = [
                                        'amount' => $amountSettled['amount'],
                                        'transfer_id' => $amountSettled['id'],
                                        'destination_account' => $amountSettled['destination'],
                                        'destination_payment' => $amountSettled['destination_payment'],
                                        'connect_salon_account_id' => $connectSalonAccountData->id,
                                        'status' => 1
                                    ];

                            $salonPayouts = $salonPayoutModel->newEntity();
                            $salonPayouts = $salonPayoutModel->patchEntity($salonPayouts, $data);
                            
                            if (!$salonPayoutModel->save($salonPayouts)) {
                                Log::write('error', $salonPayouts);
                                throw new Exception("Salon Payout could not be saved.");
                            }
                            $this->out($salonPayouts);
                            Log::write('debug', $salonPayouts);
                            $updateAppointmentSettlementStatus = $this->Appointments->updateAll(
                                                                            ['earning_settlement' => true], // fields
                                                                            ['id IN' => $payoutData['appointments']] // conditions
                                                                        );
                            $this->out($updateAppointmentSettlementStatus);
                            Log::write('debug', $updateAppointmentSettlementStatus);
                            Log::write('debug', "Earning Settlement Status has been updated for those corresponding Appointments.");
                            $this->out("Earning Settlement Status has been updated for those corresponding Appointments.");
                        }else{
                            $this->out("This account has not been connected on Stripe. Please connect first for the payout.");
                        }
                    }
                }

            }else{
                $this->out("No Payout has been transfer.");
            }

    }
}
