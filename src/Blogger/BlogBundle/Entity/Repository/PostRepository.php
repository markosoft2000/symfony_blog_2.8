<?php

namespace Blogger\BlogBundle\Entity\Repository;

use Blogger\BlogBundle\Entity\Post;
use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    /**
     * @return Post[]
     */
    public function getLatestPosts()
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p, c')
            ->leftJoin('p.comments', 'c')
            ->addOrderBy('p.created', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @return string[]
     */
    public function getTags()
    {
        $postTags = $this->createQueryBuilder('p')
            ->select('p.tags')
            ->getQuery()
            ->getResult();

        $tags = array();
        foreach ($postTags as $postTag) {
            $tags = array_merge(explode(",", $postTag['tags']), $tags);
        }

        foreach ($tags as &$tag) {
            $tag = trim($tag);
        }

        return $tags;
    }

    /**
     * @param string[] $tags
     * @return float[]
     */
    public function getTagWeights($tags)
    {
        $tagWeights = [];

        if (empty($tags)) {
            return $tagWeights;
        }

        foreach ($tags as $tag) {
            $tagWeights[$tag] = (isset($tagWeights[$tag])) ? $tagWeights[$tag] + 1 : 1;
        }
        // Shuffle the tags
        uksort($tagWeights, function() {
            return rand() > rand();
        });

        $max = max($tagWeights);

        // Max of 5 weights
        $multiplier = ($max > 5) ? 5 / $max : 1;
        foreach ($tagWeights as &$tag)
        {
            $tag = ceil($tag * $multiplier);
        }

        return $tagWeights;
    }
}
