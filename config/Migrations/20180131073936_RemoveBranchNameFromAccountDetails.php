<?php
use Migrations\AbstractMigration;

class RemoveBranchNameFromAccountDetails extends AbstractMigration
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
        $table = $this->table('account_details');
        $table->removeColumn('branch_name')
              ->save();
    }
}
