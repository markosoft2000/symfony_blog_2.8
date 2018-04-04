<?php

namespace Blogger\BlogBundle\Tests\Twig\Extensions;

use Blogger\BlogBundle\Twig\Extensions\BloggerBlogExtension;

class BloggerBlogExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testCreatedAgo()
    {
        $post = new BloggerBlogExtension();

        $this->assertEquals("0 seconds ago", $post->createdAgo(new \DateTime()));
        $this->assertEquals("34 seconds ago", $post->createdAgo($this->getDateTime(-34)));
        $this->assertEquals("1 minute ago", $post->createdAgo($this->getDateTime(-60)));
        $this->assertEquals("2 minutes ago", $post->createdAgo($this->getDateTime(-120)));
        $this->assertEquals("1 hour ago", $post->createdAgo($this->getDateTime(-3600)));
        $this->assertEquals("1 hour ago", $post->createdAgo($this->getDateTime(-3601)));
        $this->assertEquals("2 hours ago", $post->createdAgo($this->getDateTime(-7200)));

        // Cannot create time in the future
        $this->setExpectedException('\InvalidArgumentException');
        $post->createdAgo($this->getDateTime(60));
    }

    protected function getDateTime($delta)
    {
        return new \DateTime(date("Y-m-d H:i:s", time()+$delta));
    }
}