<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExpertCardsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExpertCardsTable Test Case
 */
class ExpertCardsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ExpertCardsTable
     */
    public $ExpertCards;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.expert_cards',
        'app.experts',
        'app.users',
        'app.roles',
        'app.availabilities',
        'app.expert_locations',
        'app.expert_specialization_services',
        'app.expert_specializations',
        'app.specializations',
        'app.specialization_services',
        'app.stripe_customers',
        'app.stripe_cards'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ExpertCards') ? [] : ['className' => ExpertCardsTable::class];
        $this->ExpertCards = TableRegistry::get('ExpertCards', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ExpertCards);

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
