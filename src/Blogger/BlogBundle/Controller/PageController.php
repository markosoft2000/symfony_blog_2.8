<?php

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Entity\Post;
use Blogger\BlogBundle\Entity\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Blogger\BlogBundle\Entity\Enquiry;
use Blogger\BlogBundle\Form\EnquiryType;

class PageController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        /** @var PostRepository $postRepository */
        $postRepository = $em->getRepository('BloggerBlogBundle:Post');
        /** @var Post[] $posts */
        $posts = $postRepository->getLatestPosts();

        return $this->render('BloggerBlogBundle:Page:index.html.twig', [
            'posts' => $posts
        ]);
    }

    public function aboutAction()
    {
        return $this->render('BloggerBlogBundle:Page:about.html.twig');
    }

    public function contactAction(Request $request)
    {
        $enquiry = new Enquiry();

        $form = $this->createForm(EnquiryType::class, $enquiry);

        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $message = \Swift_Message::newInstance()
                    ->setSubject('Contact enquiry from symblog')
                    ->setFrom('markosoft2000@yandex.ru')
                    ->setTo($this->container->getParameter('blogger_blog.emails.contact_email'))
                    ->setBody(
                        $this->renderView(
                            'BloggerBlogBundle:Page:contactEmail.txt.twig',
                            ['enquiry' => $enquiry]
                        )
                    );

                $this->get('mailer')->send($message);

                $this->get('session')
                    ->getFlashBag()
                    ->add('blogger-notice', 'Your contact enquiry was successfully sent. Thank you!');

                // Redirect - This is important to prevent users re-posting the form if they refresh the page
                return $this->redirect($this->generateUrl('BloggerBlogBundle_contact'));
            }
        }

        return $this->render('BloggerBlogBundle:Page:contact.html.twig', [
            'form' => $form->createView()
        ]);


    }
}