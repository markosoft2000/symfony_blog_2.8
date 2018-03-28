<?php

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\Comment;
use Blogger\BlogBundle\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Comment controller.
 */
class CommentController extends Controller
{
    public function newAction($postId)
    {
        $post = $this->getPost($postId);

        $comment = new Comment();
        $comment->setPost($post);
        $form   = $this->createForm(CommentType::class, $comment);

        return $this->render('BloggerBlogBundle:Comment:form.html.twig', [
            'comment' => $comment,
            'form'   => $form->createView()
        ]);
    }

    public function createAction(Request $request, $postId)
    {
        $post = $this->getPost($postId);

        $comment  = new Comment();
        $comment->setPost($post);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirect($this->generateUrl('BloggerBlogBundle_post_show', [
                    'id' => $comment->getPost()->getId()]) .
                '#comment-' . $comment->getId()
            );
        }

        return $this->render('BloggerBlogBundle:Comment:create.html.twig', [
            'comment' => $comment,
            'form'    => $form->createView()
        ]);
    }

    /**
     * @param $postId
     * @return null|Post
     */
    protected function getPost($postId)
    {
        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('BloggerBlogBundle:Post')->find($postId);

        if (!$post) {
            throw $this->createNotFoundException('Unable to find Post.');
        }

        return $post;
    }

}