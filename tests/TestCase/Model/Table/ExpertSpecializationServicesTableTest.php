<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExpertSpecializationServicesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExpertSpecializationServicesTable Test Case
 */
class ExpertSpecializationServicesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ExpertSpecializationServicesTable
     */
    public $ExpertSpecializationServices;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.expert_specialization_services',
        'app.experts',
        'app.users',
        'app.roles',
        'app.social_connections',
        'app.user_salons',
        'app.expert_availabilities',
        'app.appointments',
        'app.services',
        'app.appointment_transactions',
        'app.charges',
        'app.expert_cards',
        'app.expert_locations',
        'app.expert_specializations',
        'app.specializations',
        'app.specialization_services'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ExpertSpecializationServices') ? [] : ['className' => ExpertSpecializationServicesTable::class];
        $this->ExpertSpecializationServices = TableRegistry::get('ExpertSpecializationServices', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ExpertSpecializationServices);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test beforeSave method
     *
     * @return void
     */
    public function testBeforeSave()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
