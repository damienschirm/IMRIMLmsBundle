<?php

namespace IMRIM\Bundle\LmsBundle\Tests\Entity;

use IMRIM\Bundle\LmsBundle\Entity\Course;

/**
 * Test class for Course.
 */
class CourseTest extends \PHPUnit_Framework_TestCase 
{

    /**
     * @var Course
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() 
    {
        $this->object = new Course();    
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() 
    {    
    }

    /**
     * @covers Course::getCreationTime()
     */
    public function testGetCreationTime() 
    {
        $this->assertEquals($this->object->getCreationTime(),new \DateTime("now"));
    }


    /**
     * @covers Course::getUpdateTime()
     */
    public function testGetUpdateTime() 
    {
        $this->assertEquals($this->object->getUpdateTime(),new \DateTime("now"));
    }
}