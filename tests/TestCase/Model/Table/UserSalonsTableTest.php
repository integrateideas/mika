<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UserSalonsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UserSalonsTable Test Case
 */
class UserSalonsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UserSalonsTable
     */
    public $UserSalons;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.user_salons',
        'app.users',
        'app.roles',
        'app.appointments',
        'app.experts',
        'app.expert_availabilities',
        'app.expert_cards',
        'app.expert_locations',
        'app.expert_specialization_services',
        'app.expert_specializations',
        'app.specializations',
        'app.specialization_services',
        'app.user_favourite_experts',
        'app.conversations',
        'app.transactions',
        'app.user_cards',
        'app.appointment_services',
        'app.appointment_reviews',
        'app.social_connections',
        'app.user_device_tokens'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UserSalons') ? [] : ['className' => UserSalonsTable::class];
        $this->UserSalons = TableRegistry::get('UserSalons', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UserSalons);

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
