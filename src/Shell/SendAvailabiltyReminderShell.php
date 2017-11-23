<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Log\Log;
use App\Controller\AppHelper;
use Cake\Collection\Collection;
use Cake\Controller\Controller;
/**
 * SendAvailabiltyReminder shell command.
 */
class SendAvailabiltyReminderShell extends Shell
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

    public function sendSchedulingMessageToExperts(){

        $this->loadModel('Users');
        $users = $this->Users->find()->where(['role_id' => 3])->all()->indexBy('id')->toArray();
        $data = [];
        foreach ($users as $key => $value) {
            $data[] = [
                        'user_id' => $value->id,
                        'block_identifier' => "Scheduling_Availabilities",
                        'status' => 0
                    ];
        }
        if(!empty($data)){
            $appHelper = new AppHelper();
            $reqData = $appHelper->createManyConversation($data);
            print_r($reqData);
            $usersIds = (new Collection($reqData))->extract('user_id')->toArray();
            
            if(!empty($usersIds)){
                $this->_sendNotifications($usersIds);
            }else{
                Log::write('debug',"No Bookings for rejection");
                $this->out('No Bookings for rejection');
                return false;   
            }
            $this->_sendNotifications($rejectionUserIds);
            $this->out('Conversations have been saved ');
        }
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
