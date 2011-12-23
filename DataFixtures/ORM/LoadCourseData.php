<?php
namespace IMRIM\Bundle\LmsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use IMRIM\Bundle\LmsBundle\Entity\Course;

class LoadCourseData implements FixtureInterface {
    
    public function load($em){
        
        $testCourse1 = new Course();
        $testCourse1->setName("Test course 1");
        $testCourse1->setSummary("<span>Summary</span> test 1. ");
        
        $testCourse2 = new Course();
        $testCourse2->setName("Test course 2");
        $testCourse2->setSummary("<span>Summary</span> test 2. ");
        $testCourse2->setAutoInscription(true);
        
        $em->persist($testCourse1);
        $em->persist($testCourse2);
        
        $em->flush();
    }
}
