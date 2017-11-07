<?php
namespace App\Test\TestCase\Shell;

use App\Shell\SendAvailabiltyReminderShell;
use Cake\TestSuite\TestCase;

/**
 * App\Shell\SendAvailabiltyReminderShell Test Case
 */
class SendAvailabiltyReminderShellTest extends TestCase
{

    /**
     * ConsoleIo mock
     *
     * @var \Cake\Console\ConsoleIo|\PHPUnit_Framework_MockObject_MockObject
     */
    public $io;

    /**
     * Test subject
     *
     * @var \App\Shell\SendAvailabiltyReminderShell
     */
    public $SendAvailabiltyReminder;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->io = $this->getMockBuilder('Cake\Console\ConsoleIo')->getMock();
        $this->SendAvailabiltyReminder = new SendAvailabiltyReminderShell($this->io);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SendAvailabiltyReminder);

        parent::tearDown();
    }

    /**
     * Test getOptionParser method
     *
     * @return void
     */
    public function testGetOptionParser()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test main method
     *
     * @return void
     */
    public function testMain()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
