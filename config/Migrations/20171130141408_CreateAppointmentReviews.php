<?php
use Migrations\AbstractMigration;

class CreateAppointmentReviews extends AbstractMigration
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
        $table = $this->table('appointment_reviews');
        $table->addColumn('expert_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('user_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('appointment_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('rating', 'integer', [
            'default' => null,
            'limit' => 5,
            'null' => false,
        ]);
        $table->addColumn('review', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('is_approved', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('is_deleted', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('status', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('remark', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->create();
    }
}
