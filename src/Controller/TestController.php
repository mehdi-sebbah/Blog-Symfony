<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;





class TestController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {   
        $posts = $this->getDoctrine()->getRepository(Post::class)->getAllPosts();
        return $this->render("index.html.twig", [
            "posts" => $posts
        ]);
    }

    /**
     * @Route("/read-{id}", name="blog_read")
     */
    public function read(Post $post): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        return $this->render("read.html.twig", [
            "post" => $post,
            "form" => $form->createView(),
        ]);
    }
}