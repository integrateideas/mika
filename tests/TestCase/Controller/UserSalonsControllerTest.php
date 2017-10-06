<?php
namespace App\Test\TestCase\Controller;

use App\Controller\UserSalonsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\UserSalonsController Test Case
 */
class UserSalonsControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.user_salons',
        'app.users',
        'app.roles',
        'app.experts',
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
