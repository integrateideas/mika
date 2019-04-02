<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ConnectSalonAccountsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\ConnectSalonAccountsController Test Case
 */
class ConnectSalonAccountsControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.connect_salon_accounts',
        'app.stripe_user_accounts',
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
        'app.user_device_tokens',
        'app.account_details',
        'app.stripe_bank_accounts',
        'app.stripe_customers'
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
