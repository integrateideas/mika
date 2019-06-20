<?php
namespace App\Controller\Salon;

use App\Controller\Salon\AppController;

/**
 * ConnectSalonAccounts Controller
 *
 * @property \App\Model\Table\ConnectSalonAccountsTable $ConnectSalonAccounts
 *
 * @method \App\Model\Entity\ConnectSalonAccount[] paginate($object = null, array $settings = [])
 */
class SalonPayoutsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */

    public function index()
    {
        $payouts = $this->SalonPayouts->find()->contain(['ConnectSalonAccounts.UserSalons.Users'])->all()->toArray();
        $reqData = [];
        foreach ($payouts as $payout) {
            $getAccountDetails = [];
            $getAccountDetails = $this->loadComponent('Stripe')->RetrieveAccountDetails($payout->destination_account);
            $getAccountDetails = $getAccountDetails['external_accounts']['data'][0];
            $reqData[] = [
                            'payout_account' => $getAccountDetails['account'],
                            'bank_name' => $getAccountDetails['bank_name'],
                            'account_holder_name' => $getAccountDetails['account_holder_name'],
                            'payout_amount' => $payout->amount,
                            'salon_name' => $payout->connect_salon_account->user_salon->salon_name,
                            'salon_owner' => $payout->connect_salon_account->user_salon->user->first_name.' ' .$payout->connect_salon_account->user_salon->user->last_name,
                            'payout_date' =>  $payout->created
                        ];
            
        }
        
        $this->set(compact('reqData'));
        $this->set('_serialize', ['reqData']);
    }

}
