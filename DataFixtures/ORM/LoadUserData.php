<?php
namespace IMRIM\Bundle\LmsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use IMRIM\Bundle\LmsBundle\Entity\User;
use IMRIM\Bundle\LmsBundle\Entity\StudentRole;
use IMRIM\Bundle\LmsBundle\Entity\TeacherRole;
use IMRIM\Bundle\LmsBundle\Entity\AdminRole;

class LoadUserData implements FixtureInterface {
    
    public function load($em){
        
        /* Create lucie.ginette with teacher role and student role */
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
        
        /* Create marc.thomas with admin role */
        $testUser2 = new User();
        $testUser2->setLogin("marc.thomas");
        $testUser2->setFirstName("Marc");
        $testUser2->setLastName("THOMAS");
        $testUser2->setMail("marc.thomas@lms.local");
        $testUser2->setPassword(sha1("5678"));
        $testUser2->setAuthType('internal');
        $testUser2->setSalt('');
        $testUser2->setAdminRole(new AdminRole());
        
        /* Creation of 10 generic sample students */
        for ($i=0; $i<10; $i++){
            $testUser = new User();
            $testUser->setLogin("student.$i");
            $testUser->setFirstName("StudentFirstName $i");
            $testUser->setLastName("StudentLastName $i");
            $testUser->setMail("student.$i@lms.local");
            $testUser->setPassword(sha1("student.$i"));
            $testUser->setAuthType('internal');
            $testUser->setSalt('');
            $testUser->setStudentRole(new StudentRole());
            $em->persist($testUser);
        }
        
        $em->persist($testUser1);
        $em->persist($testUser2);
        $em->flush();        
    }
}