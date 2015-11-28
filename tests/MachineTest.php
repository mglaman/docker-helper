<?php
/**
 * Created by PhpStorm.
 * User: mglaman
 * Date: 11/28/15
 * Time: 2:05 PM
 */

namespace mglaman\Docker\Tests;


use mglaman\Docker\Machine;

class MachineTest extends \PHPUnit_Framework_TestCase
{

    public function testExists()
    {
        $this->assertTrue(Machine::exists());
    }

    public function testStatus()
    {
        $status = Machine::status('default');
        $this->assertTrue(in_array($status, [Machine::RUNNING, Machine::STOPPED]));
    }

    public function testStartAndStop()
    {
        $status = Machine::status('default');
        if ($status == Machine::RUNNING) {
            Machine::stop('default');
        }

        $this->assertTrue(Machine::status('default') == Machine::STOPPED);
        $this->assertTrue(Machine::start('default'));
        $this->assertTrue(Machine::status('default') == Machine::RUNNING);
        Machine::stop('default');
        $this->assertTrue(Machine::status('default') == Machine::STOPPED);
    }

    public function testGetEnv()
    {
        $status = Machine::status('default');
        if ($status == Machine::RUNNING) {
            Machine::stop('default');
        }

        try {
            Machine::getEnv('default');
            $this->fail('Docker machine is not running, expected exception');
        } catch (\Exception $e) {
            Machine::start('default');
            $env = Machine::getEnv('default');
            $this->assertNotEmpty($env);
        }
    }


}
