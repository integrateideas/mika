<?php
namespace App\Shell;

use Cake\Console\Shell;

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

    // public function 

    private function _sendNotifications($data){
        $controller = new Controller();
        $notificationComponent = $controller->loadComponent('FCMNotification');
        $this->loadModel('Users');
        $appHelper = new AppHelper();
        $getNotificationContent = $appHelper->getNotificationText('scheduling_availabilities');
        $this->loadModel('Experts');
        $deviceTokens = $this->Users->UserDeviceTokens->find()->where(['user_id IN' => $data])->all()->extract('device_token')->toArray();
        
        if(!empty($deviceTokens)){
                $title = $getNotificationContent['title'];
                $body = $getNotificationContent['body'];
                $data = ['notificationType' => $getNotificationContent['title']];
                $notification = $notificationComponent->sendToExpertApp($title, $body, $deviceTokens, $data);
                            $title = $getNotificationContent['title'];
                
        }else{
            throw new NotFoundException(__('Device token has not been found.'));
        }   
    }
}
