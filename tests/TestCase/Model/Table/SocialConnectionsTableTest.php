<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SocialConnectionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SocialConnectionsTable Test Case
 */
class SocialConnectionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SocialConnectionsTable
     */
    public $SocialConnections;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.social_connections',
        'app.users',
        'app.roles',
        'app.experts',
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
        $config = TableRegistry::exists('SocialConnections') ? [] : ['className' => SocialConnectionsTable::class];
        $this->SocialConnections = TableRegistry::get('SocialConnections', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SocialConnections);

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
