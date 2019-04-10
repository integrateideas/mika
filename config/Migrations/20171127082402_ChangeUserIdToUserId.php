<?php
use Migrations\AbstractMigration;

class ChangeUserIdToUserId extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function up()
    {
        $conversations = $this->table('conversations');
        $conversations->changeColumn('user_id', 'integer',array('null' => true))
                         ->save();
    }
    public function down(){
        $conversations = $this->table('conversations');
        $conversations->changeColumn('user_id', 'integer',array('null' => false))
                         ->save(); 
    }
}
