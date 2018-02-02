<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AppointmentReviewsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AppointmentReviewsTable Test Case
 */
class AppointmentReviewsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AppointmentReviewsTable
     */
    public $AppointmentReviews;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.appointment_reviews',
        'app.experts',
        'app.users',
        'app.roles',
        'app.appointments',
        'app.expert_availabilities',
        'app.transactions',
        'app.user_cards',
        'app.appointment_services',
        'app.expert_specializations',
        'app.specializations',
        'app.specialization_services',
        'app.expert_specialization_services',
        'app.social_connections',
        'app.user_favourite_experts',
        'app.user_salons',
        'app.account_details',
        'app.user_device_tokens',
        'app.expert_cards',
        'app.expert_locations',
        'app.conversations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AppointmentReviews') ? [] : ['className' => AppointmentReviewsTable::class];
        $this->AppointmentReviews = TableRegistry::get('AppointmentReviews', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AppointmentReviews);

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
