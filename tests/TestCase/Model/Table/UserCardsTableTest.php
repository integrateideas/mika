<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UserCardsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UserCardsTable Test Case
 */
class UserCardsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UserCardsTable
     */
    public $UserCards;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.user_cards',
        'app.users',
        'app.roles',
        'app.appointments',
        'app.experts',
        'app.user_salons',
        'app.expert_availabilities',
        'app.expert_cards',
        'app.expert_locations',
        'app.expert_specialization_services',
        'app.expert_specializations',
        'app.specializations',
        'app.specialization_services',
        'app.user_favourite_experts',
        'app.appointment_transactions',
        'app.social_connections'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UserCards') ? [] : ['className' => UserCardsTable::class];
        $this->UserCards = TableRegistry::get('UserCards', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UserCards);

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
