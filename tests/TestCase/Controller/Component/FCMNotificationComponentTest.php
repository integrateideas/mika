<?php
namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\FCMNotificationComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\FCMNotificationComponent Test Case
 */
class FCMNotificationComponentTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Controller\Component\FCMNotificationComponent
     */
    public $FCMNotification;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->FCMNotification = new FCMNotificationComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FCMNotification);

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
