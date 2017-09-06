<?php
use Phinx\Seed\AbstractSeed;

/**
 * Specializations seed.
 */
class SpecializationsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
                    [ 'name'    => 'hair_stylist',
                      'label'   =>'Hair Stylist',
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),],
                      [ 'name'    => 'barber',
                      'label'   =>'Barber',
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),],
                      [ 'name'    => 'nail_technician',
                      'label'   =>'Nail Technician',
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),]
                    
                     
        ];

        $table = $this->table('specializations');
        $table->insert($data)->save();
    }
}
