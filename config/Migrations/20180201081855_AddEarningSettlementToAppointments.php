<?php
use Migrations\AbstractMigration;

class AddEarningSettlementToAppointments extends AbstractMigration
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
        $table = $this->table('appointments');
        $table->addColumn('earning_settlement', 'boolean', [
            'default' => 0,
            'null' => true,
        ]);
        $table->update();
    }
}
