<?php
namespace App\Test\TestCase\Controller;

use App\Controller\UserFavouriteExpertsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\UserFavouriteExpertsController Test Case
 */
class UserFavouriteExpertsControllerTest extends IntegrationTestCase
{

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
