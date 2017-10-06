<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExpertAvailabilitiesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExpertAvailabilitiesTable Test Case
 */
class ExpertAvailabilitiesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ExpertAvailabilitiesTable
     */
    public $ExpertAvailabilities;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.expert_availabilities',
        'app.experts',
        'app.users',
        'app.roles',
        'app.social_connections',
        'app.user_salons',
        'app.availabilities',
        'app.expert_cards',
        'app.stripe_customers',
        'app.stripe_cards',
        'app.expert_locations',
        'app.expert_specialization_services',
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
        $config = TableRegistry::exists('ExpertAvailabilities') ? [] : ['className' => ExpertAvailabilitiesTable::class];
        $this->ExpertAvailabilities = TableRegistry::get('ExpertAvailabilities', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ExpertAvailabilities);

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
