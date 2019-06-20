<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AccountDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AccountDetailsTable Test Case
 */
class AccountDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AccountDetailsTable
     */
    public $AccountDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.account_details',
        'app.user_salons',
        'app.users',
        'app.roles',
        'app.appointments',
        'app.experts',
        'app.appointment_reviews',
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
        $config = TableRegistry::exists('AccountDetails') ? [] : ['className' => AccountDetailsTable::class];
        $this->AccountDetails = TableRegistry::get('AccountDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AccountDetails);

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
