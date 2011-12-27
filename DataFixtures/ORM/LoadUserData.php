<?php
namespace IMRIM\Bundle\LmsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use IMRIM\Bundle\LmsBundle\Entity\User;
use IMRIM\Bundle\LmsBundle\Entity\StudentRole;
use IMRIM\Bundle\LmsBundle\Entity\TeacherRole;
use IMRIM\Bundle\LmsBundle\Entity\AdminRole;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    private $container; 

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function load($em){
        
        /* Create lucie.ginette with teacher role and student role */
        $testUser1 = new User();
        $testUser1->setLogin("lucie.ginette");
        $testUser1->setFirstName("Lucie");
        $testUser1->setLastName("GINETTE");
        $testUser1->setMail("lucie.ginette@lms.local");
        $testUser1->setAuthType('internal');
        $testUser1->setSalt(md5(time()));
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($testUser1);
        $testUser1->setPassword($encoder->encodePassword('1234', $testUser1->getSalt()));
        $testUser1->setStudentRole(new StudentRole());
        $testUser1->setTeacherRole(new TeacherRole());
        
        /* Create marc.thomas with admin role */
        $testUser2 = new User();
        $testUser2->setLogin("marc.thomas");
        $testUser2->setFirstName("Marc");
        $testUser2->setLastName("THOMAS");
        $testUser2->setMail("marc.thomas@lms.local");
        $testUser2->setAuthType('internal');
        $testUser2->setSalt(md5(time()));
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($testUser2);
        $testUser2->setPassword($encoder->encodePassword('5678', $testUser2->getSalt()));
        $testUser2->setAdminRole(new AdminRole());
        
        /* Creation of 10 generic sample students */
        for ($i=0; $i<10; $i++){
            $testUser = new User();
            $testUser->setLogin('student.' . $i);
            $testUser->setFirstName('StudentFirstName ' . $i);
            $testUser->setLastName('StudentLastName ' . $i);
            $testUser->setMail('student.' . $i . '@lms.local');
            $testUser->setAuthType('internal');
            $testUser->setSalt(md5(time()));
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($testUser);
            $testUser->setPassword($encoder->encodePassword('student' . $i, $testUser->getSalt()));
            $testUser->setStudentRole(new StudentRole());
            $em->persist($testUser);
        }
        
        $em->persist($testUser1);
        $em->persist($testUser2);
        $em->flush();        
    }
    
    public function getOrder()
    {
        return 1; //the order in which fixtures will be loaded
    }
}