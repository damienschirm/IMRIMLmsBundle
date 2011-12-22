<?php

namespace IMRIM\Bundle\LmsBundle\Tests\Entity;

use IMRIM\Bundle\LmsBundle\Entity\User;
use IMRIM\Bundle\LmsBundle\Entity\TeacherRole;
use IMRIM\Bundle\LmsBundle\Entity\StudentRole;

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
     * @covers User::getPossibleAuthTypes
     */
    public function testGetPossibleAuthTypes() 
    {
        $this->assertTrue(in_array('ldap', User::getPossibleAuthTypes()));
    }
    
    /**
     * @covers User::getRoles
     */
    public function testGetRoles()
    {
        /* Create lucie.ginette whith teacher role and student role */
        $testUser1 = new User();
        $testUser1->setStudentRole(new StudentRole());
        $testUser1->setTeacherRole(new TeacherRole());
        
        $this->assertTrue(in_array('STUDENT_ROLE', $testUser1->getRoles()));
        $this->assertTrue(in_array('TEACHER_ROLE', $testUser1->getRoles()));
        $this->assertTrue(!in_array('ADMIN_ROLE', $testUser1->getRoles()));
    }
}