<?php
namespace IMRIM\Bundle\LmsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use IMRIM\Bundle\LmsBundle\Entity\User;
use IMRIM\Bundle\LmsBundle\Entity\StudentRole;
use IMRIM\Bundle\LmsBundle\Entity\TeacherRole;

class LoadUserData implements FixtureInterface {
    
    public function load($em){
        
        /* Create lucie.ginette whith teacher role and student role */
        $testUser1 = new User();
        $testUser1->setLogin("lucie.ginette");
        $testUser1->setFirstName("Lucie");
        $testUser1->setLastName("GINETTE");
        $testUser1->setMail("lucie.ginette@lms.local");
        $testUser1->setPassword(sha1("1234"));
        $testUser1->setAuthType('internal');
        $testUser1->setSalt('');
        $testUser1->setStudentRole(new StudentRole());
        $testUser1->setTeacherRole(new TeacherRole());
        
        $em->persist($testUser1);
        $em->flush();
        
    }
}