<?php

namespace IMRIM\Bundle\LmsBundle\Tests\Entity;

use IMRIM\Bundle\LmsBundle\Entity\UserGroup;

/**
 * Test class for UserGroup.
 */
class UserGroupTest extends \PHPUnit_Framework_TestCase 
{

    /**
     * @var UserGroup
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() 
    {
        $this->object = new UserGroup();    
    }

    /**
     * Tears down the fixture, for UserGroupple, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() 
    {    
    }

    /**
     * @covers UserGroup::getCreationTime()
     */
    public function testGetCreationTime() 
    {
        $this->assertEquals($this->object->getCreationTime(),new \DateTime("now"));
    }


    /**
     * @covers UserGroup::getUpdateTime()
     */
    public function testGetUpdateTime() 
    {
        $this->assertEquals($this->object->getUpdateTime(),new \DateTime("now"));
    }
}