<?php

namespace IMRIM\Bundle\LmsBundle\Tests\Entity;

use IMRIM\Bundle\LmsBundle\Entity\Lesson;

/**
 * Test class for Lesson.
 */
class LessonTest extends \PHPUnit_Framework_TestCase 
{

    /**
     * @var Lesson
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Lesson();    
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {  
    }

    /**
     * @covers Lesson::getCreationTime()
     */
    public function testGetCreationTime() 
    {
        $this->assertEquals($this->object->getCreationTime(), new \DateTime("now"));
    }


    /**
     * @covers Lesson::getUpdateTime()
     */
    public function testGetUpdateTime()
    {
        $this->assertEquals($this->object->getUpdateTime(), new \DateTime("now"));
    }

    /**
     * @covers Lesson::getPossibleTypes
     */
    public function testGetPossibleTypes()
    {
        $this->assertTrue(in_array('video', Lesson::getPossibleTypes()));
    }
}
