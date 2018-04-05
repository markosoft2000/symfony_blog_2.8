<?php

namespace Blogger\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        // Check there are some blog entries on the page
        $this->assertTrue($crawler->filter('article.post')->count() > 0);

        // Find the first link, get the title, ensure this is loaded on the next page
        $postLink = $crawler->filter('article.post h2 a')->first();
        $postTitle = $postLink->text();
        $crawler = $client->click($postLink->link());

        // Check the h2 has the blog title in it
        $this->assertEquals(1, $crawler->filter('h2:contains("'. $postTitle .'")')->count());
    }

    public function testAbout()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/about');

        $this->assertEquals(1, $crawler->filter('h1:contains("About symblog")')->count());
    }

    public function testContact()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/contact');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('h1:contains("Contact symblog")')->count());

        // Select based on button value, or id or name for buttons
        $form = $crawler->selectButton('Submit')->form();

        $form['contact[name]']       = 'name';
        $form['contact[email]']      = 'email@email.com';
        $form['contact[subject]']    = 'Subject';
        $form['contact[body]']       = 'The comment body must be at least 50 characters long as there is a validation constrain on the Enquiry entity';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue($client->getResponse()->isRedirection());

        // Check email has been sent
        if ($profile = $client->getProfile())
        {
            $swiftMailerProfiler = $profile->getCollector('swiftmailer');

            // Only 1 message should have been sent
            $this->assertEquals(1, $swiftMailerProfiler->getMessageCount());

            // Get the first message
            $messages = $swiftMailerProfiler->getMessages();
            $message  = array_shift($messages);

            $symblogEmail = $client->getContainer()->getParameter('blogger_blog.emails.contact_email');
            // Check message is being sent to correct address
            $this->assertArrayHasKey($symblogEmail, $message->getTo());
        }

        // Need to follow redirect
        $crawler = $client->followRedirect();
        $this->assertTrue($crawler->filter('.blogger-notice:contains("Your contact enquiry was successfully sent. Thank you!")')->count() > 0);
    }
}