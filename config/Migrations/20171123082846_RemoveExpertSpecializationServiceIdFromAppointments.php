<?php
use Migrations\AbstractMigration;

class RemoveExpertSpecializationServiceIdFromAppointments extends AbstractMigration
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
        $table->removeColumn('expert_specialization_service_id');
        $table->update();
    }
}
