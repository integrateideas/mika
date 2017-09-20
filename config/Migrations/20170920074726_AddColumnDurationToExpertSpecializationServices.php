<?php
use Migrations\AbstractMigration;

class AddColumnDurationToExpertSpecializationServices extends AbstractMigration
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
        $table = $this->table('expert_specialization_services');
        $table->addColumn('duration', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->changeColumn('description', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
