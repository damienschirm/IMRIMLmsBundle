<?php

namespace IMRIM\Bundle\LmsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminUserControllerTest extends WebTestCase
{
    
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/admin/user/');
        // Test if the default action of the controller is correctly secured
        // 302 : redirect to login page (anonymous user not allowed)
        $this->assertTrue(302 === $client->getResponse()->getStatusCode());
        
        //Login with admin role
        $crawler = $client->followRedirect();
             
        // Check if the client is redirected on login page
        $this->assertRegExp('/login/', $client->getResponse()->getContent());
        
         // Fill in the form and submit it
        $form = $crawler->selectButton('login')->form(array(
            '_username'  => 'marc.thomas',
            '_password' => '5678',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();
        
        $crawler = $client->request('GET', '/admin/user/');
        // Test if the default action of the controller is correctly secured
        // 200 : HTTP ok
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());
        
        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'imrim_bundle_lmsbundle_usertype[login]'  => 'TestUnit',
            'imrim_bundle_lmsbundle_usertype[firstName]' => 'TestUnit\'First Name-é',
            'imrim_bundle_lmsbundle_usertype[lastName]' => 'TestUnit\'Last Name-ä',
            'imrim_bundle_lmsbundle_usertype[mail]' => 'testunit@gmail.com',
            'imrim_bundle_lmsbundle_usertype[isSuspended]' => true,
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertTrue($crawler->filter('td:contains("TestUnit")')->count() > 0);

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Edit')->form(array(
            'imrim_bundle_lmsbundle_usertype[login]'  => 'TestUnitEdit',
            'imrim_bundle_lmsbundle_usertype[firstName]' => 'TestUnitEdit\'First Name-é',
            'imrim_bundle_lmsbundle_usertype[lastName]' => 'TestUnitEdit\'Last Name-ä',
            'imrim_bundle_lmsbundle_usertype[mail]' => 'testunitedit@gmail.com',
            'imrim_bundle_lmsbundle_usertype[isSuspended]' => false,
        ));

        $client->submit($form);

        $crawler = $client->followRedirect();
        
        // Check the element contains an attribute with value equals "TestUnitEdit"
        $this->assertRegExp('/TestUnitEdit/', $client->getResponse()->getContent());

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/TestUnitEdit/', $client->getResponse()->getContent());
    }
    
}