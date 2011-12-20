<?php
namespace IMRIM\Bundle\LmsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use IMRIM\Bundle\LmsBundle\Entity\User;

class LoadUserData implements FixtureInterface {
    
    public function load($em){
        
        $testUser1 = new User();
        $testUser1->setLogin("lucie.ginette");
        $testUser1->setFirstName("Lucie");
        $testUser1->setLastName("GINETTE");
        $testUser1->setMail("lucie.ginette@lms.local");
        $testUser1->setPassword(sha1("1234"));
        $testUser1->setAuthType('internal');
        $testUser1->setSalt('vvevev');
              
        $em->persist($testUser1);
        
        $em->flush();
    }
}