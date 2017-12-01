<?php
namespace App\Test\TestCase\Controller;

use App\Controller\AppointmentReviewsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\AppointmentReviewsController Test Case
 */
class AppointmentReviewsControllerTest extends IntegrationTestCase
{

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
        'app.user_device_tokens',
        'app.expert_cards',
        'app.expert_locations',
        'app.conversations'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
