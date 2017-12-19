<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
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
use App\Controller\AppHelper;
use Cake\I18n\FrozenTime;


/**
 * Transactions Controller
 *
 * @property \App\Model\Table\TransactionsTable $Transactions
 *
 * @method \App\Model\Entity\Transactions[] paginate($object = null, array $settings = [])
 */
class TransactionsController extends ApiController
{

    public function dailyEarnings($date = null){

        if(!$this->request->is(['get'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $expertId = $this->Transactions->Appointments->Users->Experts->findByUserId($this->Auth->user('id'))->first()->id;

        $date = new FrozenTime('today');
        $startdate = $date->modify('00:05:00');
        $enddate = $date->modify('23:55:00');

        $transactions = $this->Transactions->Appointments->findByExpertId($expertId)
                                                        ->where(function ($exp) use ($startdate, $enddate) {
                                                                      return $exp->between('Appointments.created', $startdate, $enddate);
                                                                    })
                                                        ->where(['is_confirmed' => true, 'is_completed' => true])
                                                        ->contain(['Transactions'])
                                                        ->all()
                                                        ->sumOf('transaction.transaction_amount');

        $this->set('data',$transactions);
        $this->set('status',true);
        $this->set('_serialize', ['status','data']);
    }

}
