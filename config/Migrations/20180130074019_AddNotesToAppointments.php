<?php
use Migrations\AbstractMigration;

class AddNotesToAppointments extends AbstractMigration
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
        $table->addColumn('notes', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
