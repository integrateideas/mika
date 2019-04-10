<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SpecializationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SpecializationsTable Test Case
 */
class SpecializationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SpecializationsTable
     */
    public $Specializations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.specializations',
        'app.expert_specializations',
        'app.experts',
        'app.users',
        'app.roles',
        'app.social_connections',
        'app.user_salons',
        'app.appointments',
        'app.expert_availabilities',
        'app.expert_specialization_services',
        'app.specialization_services',
        'app.appointment_transactions',
        'app.expert_cards',
        'app.expert_locations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Specializations') ? [] : ['className' => SpecializationsTable::class];
        $this->Specializations = TableRegistry::get('Specializations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Specializations);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
