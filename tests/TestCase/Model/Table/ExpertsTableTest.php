<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExpertsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExpertsTable Test Case
 */
class ExpertsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ExpertsTable
     */
    public $Experts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.experts',
        'app.users',
        'app.roles',
        'app.social_connections',
        'app.user_salons',
        'app.expert_availabilities',
        'app.appointments',
        'app.expert_specialization_services',
        'app.expert_specializations',
        'app.specializations',
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
        $config = TableRegistry::exists('Experts') ? [] : ['className' => ExpertsTable::class];
        $this->Experts = TableRegistry::get('Experts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Experts);

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
}
