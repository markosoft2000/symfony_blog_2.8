<?php

namespace Blogger\BlogBundle\Tests\Entity;

use Blogger\BlogBundle\Entity\Post;

class PostTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Post
     */
    private $post;

    public function setUp()
    {
        $this->post = new Post();

        parent::setUp();
    }

    public function tearDown()
    {
        unset($this->post);

        parent::tearDown();
    }

    public function providerTestSlug()
    {
        return [
            array('Hello World', 'hello-world'),
            array('A Day With Symfony2', 'a-day-with-symfony2'),
            array('Hello world', 'hello-world'),
            array('symblog ', 'symblog'),
            array(' symblog', 'symblog'),
        ];
    }

    /**
     * @dataProvider providerTestSlug
     * @param string $originalString
     * @param string $expectedResult
     */
    public function testSlugifyWithDataProvider($originalString, $expectedResult)
    {
        $this->assertEquals($expectedResult, $this->post->slugify($originalString));
    }

    public function testPrivateWithReflection()
    {
        $reflection = new \ReflectionClass(get_class($this->post));
        $method = $reflection->getMethod('slugify');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->post, ['A Day With Symfony2']);
        $this->assertEquals('a-day-with-symfony2', $result);
    }

    public function testSlugify()
    {
        $post = new Post();

        $this->assertEquals('hello-world', $post->slugify('Hello World'));
        $this->assertEquals('a-day-with-symfony2', $post->slugify('A Day With Symfony2'));
        $this->assertEquals('hello-world', $post->slugify('Hello world'));
        $this->assertEquals('symblog', $post->slugify('symblog '));
        $this->assertEquals('symblog', $post->slugify(' symblog'));
    }

    public function testSetSlug()
    {
        $post = new Post();

        $post->setSlug('Symfony2 Blog');
        $this->assertEquals('symfony2-blog', $post->getSlug());
    }

    public function testSetTitle()
    {
        $post = new Post();

        $post->setTitle('Hello World');
        $this->assertEquals('hello-world', $post->getSlug());
    }
}