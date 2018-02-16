<?php
use Migrations\AbstractMigration;

class RemoveAccountDetailIdFromSalonPayouts extends AbstractMigration
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
        $table = $this->table('salon_payouts');
        $table->removeColumn('account_detail_id')
              ->save();
    }
}
