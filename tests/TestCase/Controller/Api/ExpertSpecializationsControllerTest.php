<?php
namespace App\Test\TestCase\Controller\Api;

use App\Controller\Api\ExpertSpecializationsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\Api\ExpertSpecializationsController Test Case
 */
class ExpertSpecializationsControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.expert_specializations',
        'app.experts',
        'app.users',
        'app.roles',
        'app.availabilities',
        'app.expert_locations',
        'app.expert_specialization_services',
        'app.specialization_services',
        'app.specializations'
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
