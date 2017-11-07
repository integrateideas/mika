<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Log\Log;

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
        $this->loadModel('Conversations');
        $conversation = $this->Conversations->newEntities($data);
        $conversation = $this->Conversations->patchEntities($conversation,$data);
        if ($this->Conversations->saveMany($conversation,['users' => $users])){
            $this->out('Conversations have been saved ');
            print_r($conversation);
            Log::write('debug','Conversations have been saved ');
            Log::write('debug',$conversation);
        }
        
    }
}
