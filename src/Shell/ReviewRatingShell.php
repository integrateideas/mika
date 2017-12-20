<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\I18n\FrozenTime;
use Cake\Log\Log;
use App\Bandwidth\Bandwidth;
/**
 * ReviewRating shell command.
 */
class ReviewRatingShell extends Shell
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

    public function appointmentRating(){

        $currentTime = FrozenTime::now();
        $compareTime = $currentTime->modify('-1 hour');
        
        $this->loadModel('Appointments');
        $confirmedAppointments = $this->Appointments->find()
                                                    ->where(['is_confirmed' => 1,'is_completed IS NULL'])
                                                    ->contain(['ExpertAvailabilities' => function($q) use($compareTime, $currentTime){
                                                        return $q->where(function ($exp) use ($compareTime, $currentTime){
                                                           return $exp->lte('available_to',$compareTime); 
                                                        });
                                                    }])
                                                    ->all()
                                                    ->toArray();

        Log::write('debug', $confirmedAppointments);
        $appointmentCompleted = null;
        foreach ($confirmedAppointments as $key => $value) {
            if(isset($value->expert_availability) && $value->expert_availability){
                $data = ['is_completed' => 1];
                $appointmentCompleted = $this->Appointments->patchEntity($value,$data);
                Log::write('debug', 'patch Entity');
                if($this->Appointments->save($appointmentCompleted)){
                    Log::write('debug', 'saving data');
                    Log::write('debug', $appointmentCompleted);
                }
                $this->loadModel('Users');
                $reviewLink = '/user/reviewRating';
                $user = $this->Users->findById($appointmentCompleted->user_id)->first();
                $this->_sendMessage($reviewLink,$user);
                $this->out('Confirmed Appointments have been Completed');
            }    
        }
        if(!$appointmentCompleted){
            $this->out('No Appointment is there');
        }
    }

    private function _sendMessage($reviewLink,$user){
        $this->Bandwidth = new Bandwidth();
        Log::write('debug',$reviewLink);
        Log::write('debug',$user);
        $phoneNumber = $user->phone;
        $phoneNumber  = str_replace('+1', '', $phoneNumber);
        Log::write('debug',$phoneNumber);
        $this->Bandwidth->sendMessage($phoneNumber,$reviewLink);
        return true;
    }
}
