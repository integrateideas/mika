<?php
use Migrations\AbstractMigration;

class CreateAppointments extends AbstractMigration
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
        $table->addColumn('user_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('expert_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('time_from', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('time_to', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('expert_specialization_service_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('service_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('is_confirmed', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('is_completed', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}
