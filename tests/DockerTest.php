<?php
/**
 * Created by PhpStorm.
 * User: mglaman
 * Date: 11/28/15
 * Time: 1:59 PM
 */

namespace mglaman\Docker\Tests;


use mglaman\Docker\Docker;

/**
 * Tests the `docker` command tests.
 * @tag docker
 **/
class DockerTest extends \PHPUnit_Framework_TestCase
{
    public function testExists()
    {
        $this->assertTrue(Docker::exists());
    }
    public function testAvailable()
    {
        $this->assertTrue(Docker::available());
    }

}
