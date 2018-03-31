<?php

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Entity\Comment;
use Blogger\BlogBundle\Entity\Post;
use Blogger\BlogBundle\Entity\Repository\CommentRepository;
use Blogger\BlogBundle\Entity\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Blog controller.
 */
class PostController extends Controller
{
    /**
     * Show a post entry
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id/*, $slug, $comments*/)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var PostRepository $postRepository */
        $postRepository = $em->getRepository('BloggerBlogBundle:Post');
        /** @var Post $post */
        $post = $postRepository->find($id);

        if (!$post) {
            throw $this->createNotFoundException('Unable to find Post.');
        }

        /** @var CommentRepository $commentRepository */
        $commentRepository = $em->getRepository('BloggerBlogBundle:Comment');
        /** @var Comment[] $comments */
        $comments = $commentRepository->getCommentsForPost($post->getId());

        return $this->render('BloggerBlogBundle:Post:show.html.twig', [
            'post'      => $post,
            'comments'  => $comments
        ]);
    }
}