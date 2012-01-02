<?php

namespace IMRIM\Bundle\LmsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CourseControllerTest extends WebTestCase {

    public function testCreateModifyDeleteScenario() {
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/course/create');
        // Test if the default action of the controller is correctly secured
        // 302 : redirect to login page (anonymous user not allowed)
        $this->assertTrue(302 === $client->getResponse()->getStatusCode());

        //Login with admin role
        $crawler = $client->followRedirect();

        // Check if the client is redirected on login page
        $this->assertRegExp('/login/', $client->getResponse()->getContent());

        // Fill in the form and submit it
        $form = $crawler->selectButton('login')->form(array(
            '_username' => 'lucie.ginette',
            '_password' => '1234',
                ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        $crawler = $client->request('GET', '/course/create');
        // Test if the default action of the controller is correctly secured
        // 200 : HTTP ok
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());

        preg_match('#<option value="(\d+)">testCategory0</option>#', $client->getResponse()->getContent(), $match);
        $form = $crawler->selectButton("submit")->form(array(
            'imrim_bundle_lmsbundle_coursetype[name]' => 'UnitTest Course 1',
            'imrim_bundle_lmsbundle_coursetype[summary]' => 'Uni &é tTest Course 1',
            'imrim_bundle_lmsbundle_coursetype[startDate]' => '20-12-2008',
            'imrim_bundle_lmsbundle_coursetype[autoInscription]' => true,
            'imrim_bundle_lmsbundle_coursetype[category]' => $match[1],
                ));

        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertRegExp('/UnitTest Course 1/', $client->getResponse()->getContent());

        $link = $crawler->filter('a:contains("éditer")')->eq(0)->link();
        $crawler = $client->click($link);

        $this->assertTrue(200 === $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton("submit")->form(array(
            'imrim_bundle_lmsbundle_coursetype[name]' => 'UnitTest Course 1 Edited',
            'imrim_bundle_lmsbundle_coursetype[summary]' => 'Uni &é tTest Course 1',
            'imrim_bundle_lmsbundle_coursetype[startDate]' => '28-01-2011',
            'imrim_bundle_lmsbundle_coursetype[autoInscription]' => true,
            'imrim_bundle_lmsbundle_coursetype[category]' => $match[1],
                ));

        $client->submit($form);
        $this->assertRegExp('/UnitTest Course 1 Edited/', $client->getResponse()->getContent());

        $crawler->selectLink('Editer');
        $crawler = $client->click($link);
        // 200 : HTTP ok
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());

        // Tests delete of the course
        $form = $crawler->selectButton("delete")->form();
        $client->submit($form);
        $crawler = $client->followRedirect();

        // Tests if the course was correctly deleted
        $this->assertNotRegExp('/UnitTest Course 1 Edited/', $client->getResponse()->getContent());
    }

    public function testSubscribeUnsubscribeScenario() {
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
            '_username' => 'lucie.ginette',
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
