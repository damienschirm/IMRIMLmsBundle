<?php

namespace IMRIM\Bundle\LmsBundle\Tests\Entity;

use IMRIM\Bundle\LmsBundle\Entity\Exam;

/**
 * Test class for Exam.
 */
class ExamTest extends \PHPUnit_Framework_TestCase 
{

    /**
     * @var Exam
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() 
    {
        $this->object = new Exam();    
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() 
    {    
    }

    /**
     * @covers Exam::getCreationTime()
     */
    public function testGetCreationTime() 
    {
        $this->assertEquals($this->object->getCreationTime(), new \DateTime("now"));
    }


    /**
     * @covers Exam::getUpdateTime()
     */
    public function testGetUpdateTime() 
    {
        $this->assertEquals($this->object->getUpdateTime(), new \DateTime("now"));
    }

}