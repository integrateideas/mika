<?php
use Migrations\AbstractMigration;

class RemoveOverlappingAllowedFromExpertAvailabilities extends AbstractMigration
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
        $table = $this->table('expert_availabilities');
        $table->removeColumn('overlapping_allowed');
        $table->update();
    }
}
