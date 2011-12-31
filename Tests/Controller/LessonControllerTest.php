<?php

namespace IMRIM\Bundle\LmsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LessonControllerTest extends WebTestCase {

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
            'imrim_bundle_lmsbundle_coursetype[name]' => 'UnitTest CourseLesson 1',
            'imrim_bundle_lmsbundle_coursetype[summary]' => 'Uni &é tTest CourseLesson 1',
            'imrim_bundle_lmsbundle_coursetype[startDate]' => '20-12-2008',
            'imrim_bundle_lmsbundle_coursetype[autoInscription]' => true,
            'imrim_bundle_lmsbundle_coursetype[category]' => $match[1],
                ));

        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertRegExp('/UnitTest CourseLesson 1/', $client->getResponse()->getContent());

        $link = $crawler->filter('a:contains("Editer")')->eq(0)->link();
        $crawler = $client->click($link);

        $this->assertTrue(200 === $client->getResponse()->getStatusCode());

        // Lesson's add test
        $link = $crawler->filter('a:contains("Ajouter une leçon")')->eq(0)->link();
        $crawler = $client->click($link);
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton("submit")->form(array(
            'imrim_bundle_lmsbundle_lessontype[title]' => 'UnitTest Lesson 1',
            'imrim_bundle_lmsbundle_lessontype[type]' => 'html',
                ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Test if the title of the lesson has been sent
        $this->assertRegExp('/UnitTest Lesson 1/', $client->getResponse()->getContent());

        // Test if the associated course id is a valid id (id>0). 
        preg_match('#/course/(\d+)/lesson/(\d+)/edit#', $client->getResponse()->getContent(), $matches);
        $this->assertTrue($matches[1] > 0);
    }

}