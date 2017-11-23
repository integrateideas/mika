<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\NotFoundException;
use Cake\Core\Exception\Exception;
use App\Controller\AppHelper;
use Cake\I18n\FrozenTime;
use Cake\Log\Log;
use Cake\ORM\ClassRegistry;
use Cake\Core\App;
use Cake\Controller\Controller;


/**
 * RejectBooking shell command.
 */
class RejectBookingShell extends Shell
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

    public function rejectBookings(){

        $this->loadModel('Appointments');
        $currentTime = FrozenTime::now();
        $compareTime = $currentTime->modify('-15 minutes');
        $reqDataForNotification = $this->Appointments->find()->where(['is_confirmed IS NULL'], ['created <='=>$compareTime , 'is_confirmed IS NULL','is_completed IS NULL'])->all();
        $rejectionUserIds = $reqDataForNotification->extract('user_id')->toArray();
        
        if(!empty($rejectionUserIds)){
            $response = $this->Appointments->updateAll(['is_confirmed' => 0], ['created <='=>$compareTime , 'is_confirmed IS NULL','is_completed IS NULL']);
            Log::write('debug',$response);
        }else{
            Log::write('debug',"No Bookings for rejection");
            $this->out('No Bookings for rejection');
            return false;   
        }
        $this->_sendNotifications($rejectionUserIds);
        $this->out($response);
        $this->out("Pending Appointment's have been rejected");
    }

    private function _sendNotifications($data){
        $controller = new Controller();
        $notificationComponent = $controller->loadComponent('FCMNotification');
        
        $this->loadModel('Users');
        $appHelper = new AppHelper();
        $getNotificationContent = $appHelper->getNotificationText('cancel_booking');
        $deviceTokens = $this->Users->UserDeviceTokens->find()->where(['user_id IN' => $data])->all()->toArray();
        if(!empty($deviceTokens)){

            foreach ($deviceTokens as $key => $deviceToken) {
                $deviceToken = $deviceToken->device_token;
                $title = $getNotificationContent['title'];
                $body = $getNotificationContent['body'];
                $data = ['hi' => 'hello'];
                $notification = $notificationComponent->sendToExpertApp($title, $body, $deviceToken, $data);
           
            }
        }else{
            throw new NotFoundException(__('Device token has not been found.'));
        }   
    }
}
