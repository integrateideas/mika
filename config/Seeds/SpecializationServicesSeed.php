<?php
use Phinx\Seed\AbstractSeed;

/**
 * SpecializationsServices seed.
 */
class SpecializationServicesSeed extends AbstractSeed
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
                    [ 'name'    => 'smoothening',
                      'label'   =>'Smoothening',
                      'specialization_id' => 1,
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),],
                      [ 'name'    => 'hair_straightening',
                      'label'   =>'Hair Straightening',
                      'specialization_id' => 1,
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),],
                      [ 'name'    => 'deep_conditioning_treatments',
                      'label'   =>'Deep Conditioning Treatments',
                      'specialization_id' => 1,
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),],
                      [ 'name'    => 'head_massage',
                      'label'   =>'Head Massage',
                      'specialization_id' => 2,
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),],
                      [ 'name'    => 'trimming',
                      'label'   =>'Trimming',
                      'specialization_id' => 2,
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),],
                      [ 'name'    => 'manicure',
                      'label'   => 'Manicure',
                      'specialization_id' => 3,
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),],
                      [ 'name'    => 'pedicure',
                      'label'   => 'Pedicure',
                      'specialization_id' => 3,
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),],
                      [ 'name'    => 'acrylic_nails',
                      'label'   => 'Acrylic Nails',
                      'specialization_id' => 3,
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),],
                    
                     
        ];

        $table = $this->table('specialization_services');
        $table->insert($data)->save();
    }
}
