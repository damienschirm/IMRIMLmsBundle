<?php

namespace IMRIM\Bundle\LmsBundle\Tests\Entity;

use IMRIM\Bundle\LmsBundle\Entity\Config;

/**
 * Test class for Config.
 */
class ConfigTest extends \PHPUnit_Framework_TestCase 
{

    /**
     * @var Config
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() 
    {
        $this->object = new Config();    
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() 
    {    
    }

    /**
     * @covers Config::getCreationTime()
     */
    public function testGetCreationTime() 
    {
        $this->assertEquals($this->object->getCreationTime(), new \DateTime("now"));
    }


    /**
     * @covers Config::getUpdateTime()
     */
    public function testGetUpdateTime() 
    {
        $this->assertEquals($this->object->getUpdateTime(), new \DateTime("now"));
    }
}