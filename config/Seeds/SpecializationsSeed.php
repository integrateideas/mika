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
                    [ 'name'    => 'hair',
                      'label'   =>'Hair',
                      'status'=> 1,
                      'color' => '#79cfde',
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),],
                      [ 'name'    => 'massage',
                      'label'   =>'Massage',
                      'status'=> 1,
                      'color' => '#e6b6d3',
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),],
                      [ 'name'    => 'nails',
                      'label'   =>'Nails',
                      'status'=> 1,
                      'color' => '#bbce9e',
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),],
                      [ 'name'    => 'makeup',
                      'label'   =>'Makeup',
                      'status'=> 1,
                      'color' => '#faee68',
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),]
                    
                     
        ];

        $table = $this->table('specializations');
        $table->insert($data)->save();
    }
}
