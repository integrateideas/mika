<?php
use Migrations\AbstractMigration;

class AddPaymentCapturedToTransactions extends AbstractMigration
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
        $table = $this->table('transactions');
        $table->addColumn('payment_captured', 'boolean', [
            'default' => 0,
            'null' => true,
        ]);
        $table->update();
    }
}
