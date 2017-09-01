<?php
use Migrations\AbstractMigration;

class CreateAvailabilities extends AbstractMigration
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
        $table = $this->table('availabilities');
        $table->addColumn('expert_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('from', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('to', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('overlapping_allowed', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('status', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}
