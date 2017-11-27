<?php
use Migrations\AbstractMigration;

class RemoveUserIdFromConversations extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('conversations');
        $table->removeColumn('user_id');
        $table->update();
    }
}
