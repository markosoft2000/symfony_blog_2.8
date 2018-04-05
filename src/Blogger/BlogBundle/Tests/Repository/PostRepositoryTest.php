<?php

namespace Blogger\BlogBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostRepositoryTest extends WebTestCase
{
    /**
     * @var \Blogger\BlogBundle\Entity\Repository\PostRepository
     */
    private $postRepository;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->postRepository = $kernel->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository('BloggerBlogBundle:Post');
    }

    public function testGetTags()
    {
        $tags = $this->postRepository->getTags();

        $this->assertTrue(count($tags) > 1);
        $this->assertContains('symblog', $tags);
    }

    public function testGetTagWeights()
    {
        $tagsWeight = $this->postRepository->getTagWeights(
            array('php', 'code', 'code', 'symblog', 'blog')
        );

        $this->assertTrue(count($tagsWeight) > 1);

        // Test case where count is over max weight of 5
        $tagsWeight = $this->postRepository->getTagWeights(
            array_fill(0, 10, 'php')
        );

        $this->assertTrue(count($tagsWeight) >= 1);

        // Test case with multiple counts over max weight of 5
        $tagsWeight = $this->postRepository->getTagWeights(
            array_merge(array_fill(0, 10, 'php'), array_fill(0, 2, 'html'), array_fill(0, 6, 'js'))
        );

        $this->assertEquals(5, $tagsWeight['php']);
        $this->assertEquals(3, $tagsWeight['js']);
        $this->assertEquals(1, $tagsWeight['html']);

        // Test empty case
        $tagsWeight = $this->postRepository->getTagWeights(array());

        $this->assertEmpty($tagsWeight);
    }
}