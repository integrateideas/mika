<?php
use Migrations\AbstractMigration;

class RemovePayoutIdFromSalonPayouts extends AbstractMigration
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
        $table->removeColumn('payout_id')
              ->save();
    }
    
}
