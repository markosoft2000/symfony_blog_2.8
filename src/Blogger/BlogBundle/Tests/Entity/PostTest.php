<?php

namespace Blogger\BlogBundle\Tests\Entity;

use Blogger\BlogBundle\Entity\Post;

class PostTest extends \PHPUnit_Framework_TestCase
{
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