<?php

namespace IMRIM\Bundle\LmsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use IMRIM\Bundle\LmsBundle\Entity\Course;

class LoadCourseData extends AbstractFixture implements OrderedFixtureInterface {

    public function load($em) {

        $testCourse1 = new Course();
        $testCourse1->setName("Test course 1");
        $testCourse1->setSummary("<span>Summary</span> test 1. ");
        //Reference to the category created in LoadCategoryData
        //$testCourse1->setCategory($em->merge($this->getReference('category-1')));

        $testCourse2 = new Course();
        $testCourse2->setName("Test course 2");
        $testCourse2->setSummary("<span>Summary</span> test 2. ");
        $testCourse2->setAutoInscription(true);
        //$testCourse2->setCategory($em->merge($this->getReference('category-2')));

        $testCourse3 = new Course();
        $testCourse3->setName("Test course 3 (expiré)");
        $testCourse3->setSummary("<span>Summary</span> test 3. ");
        $testCourse3->setAutoInscription(true);
        $now = new \DateTime("now");
        $testCourse3->setExpirationDate($now->modify( '-1 day' ));
        
        $em->persist($testCourse1);
        $em->persist($testCourse2);
        $em->persist($testCourse3);

        $em->flush();
    }

    public function getOrder() {
        return 3; //the order in which fixtures will be loaded
    }

}
