<?php

namespace IMRIM\Bundle\LmsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminUserControllerTest extends WebTestCase
{
    
    public function testSubscribeUnsubscribeScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/course/list');
        // Test if the default action of the controller is correctly secured
        // 302 : redirect to login page (anonymous user not allowed)
        $this->assertTrue(302 === $client->getResponse()->getStatusCode());
        
        //Login with admin role
        $crawler = $client->followRedirect();
             
        // Check if the client is redirected on login page
        $this->assertRegExp('/login/', $client->getResponse()->getContent());
        
         // Fill in the form and submit it
        $form = $crawler->selectButton('login')->form(array(
            '_username'  => 'lucie.ginette',
            '_password' => '1234',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();
        
        $crawler = $client->request('GET', '/course/list');
        // Test if the default action of the controller is correctly secured
        // 200 : HTTP ok
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        
        $this->assertRegExp('/Test course 1/', $client->getResponse()->getContent());
        
        $form = $crawler->selectButton("M'inscrire")->form();
        
        $client->submit($form);
        $this->assertTrue(302 === $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        
        $this->assertRegExp('/Me d&eacute;sinscrire/', $client->getResponse()->getContent());
        $form = $crawler->selectButton("Me désinscrire")->form();
        
        $client->submit($form);
        $this->assertTrue(302 === $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertRegExp("/M'inscrire/", $client->getResponse()->getContent());
        
    }
    
}