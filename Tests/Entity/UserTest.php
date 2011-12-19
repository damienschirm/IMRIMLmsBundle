<?php

namespace IMRIM\Bundle\LmsBundle\Tests\Entity;

use IMRIM\Bundle\LmsBundle\Entity\User;

/**
 * Test class for User.
 */
class UserTest extends \PHPUnit_Framework_TestCase 
{

    /**
     * @var User
     */
    protected $object;
    
    /**
     * Sets up the fixture, for Userple, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new User();    
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() 
    {    
    }

    /**
     * @covers User::getCreationTime()
     */
    public function testGetCreationTime() 
    {
        $this->assertEquals($this->object->getCreationTime(), new \DateTime("now"));
    }


    /**
     * @covers User::getUpdateTime()
     */
    public function testGetUpdateTime() 
    {
        $this->assertEquals($this->object->getUpdateTime(), new \DateTime("now"));
    }

    /**
     * @covers Lesson::getPossibleAuthTypes
     */
    public function testGetPossibleAuthTypes() 
    {
        $this->assertTrue(in_array('ldap', User::getPossibleAuthTypes()));
    }
}