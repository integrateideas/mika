<?php
namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\StripeComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\StripeComponent Test Case
 */
class StripeComponentTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Controller\Component\StripeComponent
     */
    public $Stripe;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Stripe = new StripeComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Stripe);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
