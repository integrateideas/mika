<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UserFavouriteExpertsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UserFavouriteExpertsTable Test Case
 */
class UserFavouriteExpertsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UserFavouriteExpertsTable
     */
    public $UserFavouriteExperts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.user_favourite_experts',
        'app.users',
        'app.roles',
        'app.experts',
        'app.user_salons',
        'app.appointments',
        'app.expert_availabilities',
        'app.expert_specialization_services',
        'app.expert_specializations',
        'app.specializations',
        'app.specialization_services',
        'app.appointment_transactions',
        'app.expert_cards',
        'app.expert_locations',
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
        $config = TableRegistry::exists('UserFavouriteExperts') ? [] : ['className' => UserFavouriteExpertsTable::class];
        $this->UserFavouriteExperts = TableRegistry::get('UserFavouriteExperts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UserFavouriteExperts);

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
