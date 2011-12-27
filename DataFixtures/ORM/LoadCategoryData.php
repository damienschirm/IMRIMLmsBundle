<?php
namespace IMRIM\Bundle\LmsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use IMRIM\Bundle\LmsBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface {
    
    public function load($em){
        
        for ($i = 0; $i < 10; $i++)
        {
            $testCategory = new Category();
            $testCategory->setName('testCategory' . $i);
            $testCategory->setIsPublic(true);
            $em->persist($testCategory);
            // Creation of a reference to the object so it can be used later (in other fixtures)
            $this->addReference('category-' . $i, $testCategory);
        }
        
        $em->flush();
    }
    
    public function getOrder()
    {
        return 2; //the order in which fixtures will be loaded
    }
}
