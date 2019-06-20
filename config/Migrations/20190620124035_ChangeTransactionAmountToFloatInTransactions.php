<?php
use Migrations\AbstractMigration;

class ChangeTransactionAmountToFloatInTransactions extends AbstractMigration
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
        $conversations = $this->table('transactions');
        $conversations->changeColumn('transaction_amount', 'float',array('null' => true))
                         ->save();
    }
}
