<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SpecializationServicesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SpecializationServicesTable Test Case
 */
class SpecializationServicesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SpecializationServicesTable
     */
    public $SpecializationServices;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.specialization_services',
        'app.specializations',
        'app.expert_specializations',
        'app.expert_specialization_services'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('SpecializationServices') ? [] : ['className' => SpecializationServicesTable::class];
        $this->SpecializationServices = TableRegistry::get('SpecializationServices', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SpecializationServices);

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
