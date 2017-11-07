<?php
namespace App\Controller\Api\User;

use App\Controller\Api\User\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Core\Exception\Exception;
use Cake\I18n\FrozenTime;

class ExpertProfileController extends ApiController
{

    /**
     * View method
     *
     * @return \Cake\Http\Response|void
     */
    public function view($expertId)
    {
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        if(!isset($expertId) || !$expertId){
            throw new MethodNotAllowedException(__('MANDATORY_ARGUMENT_MISSING','expert_id'));
        }
        
        $this->loadModel('Experts');
        $expertProfile = $this->Experts->findById($expertId)
                                        ->contain(['Users','ExpertSpecializations'  => function($q){
                                return $q->contain(['ExpertSpecializationServices.SpecializationServices','Specializations']);}])
                                        ->first();

        $this->set('data',$expertProfile);
        $this->set('_serialize', ['data']);
    }

    public function todaysAvailabilities($expertId = null)
    {
        if (!$this->request->is(['get'])) {
          throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        if(!isset($expertId) || !$expertId){
            throw new MethodNotAllowedException(__('MANDATORY_ARGUMENT_MISSING','expert_id'));
        }

        $date = new FrozenTime('today');
        $startdate = $date->modify('00:05:00');
        $enddate = $date->modify('23:55:00');

        $this->loadModel('ExpertAvailabilities');
        $todaysAvailabilities = $this->ExpertAvailabilities
                                        ->findByExpertId($expertId)
                                        ->where([function ($exp) use ($startdate, $enddate) {
                                                              return $exp->between('available_from', $startdate, $enddate);
                                                            }])
                                        ->all();

        $this->set('data',$todaysAvailabilities);
        $this->set('status',true);
        $this->set('_serialize', ['status','data']);
    }
}
