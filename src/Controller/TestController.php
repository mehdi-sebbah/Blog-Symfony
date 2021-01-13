<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {   
        $limit = $request->get("limit", 10);
        $page = $request->get("page", 1);
        $total = $this->getDoctrine()->getRepository(Post::class)->count([]);
        $posts = $this->getDoctrine()->getRepository(Post::class)->getPaginatedPosts(
            $page,
            $limit
        );
        $pages = $total / $limit;
        return $this->render("index.html.twig", [
            "posts" => $posts,
            "pages" => $pages,
            "page" => $page,
            "limit" => $limit,
            ]);
    }

    /**
     * @Route("/read-{id}", name="blog_read")
     */
    public function read(Post $post, Request $request): Response
    {
        $comment = new Comment();
        //Permet de faire la liaison avec le post en question.
        $comment->setPost($post);

        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
            //Toujours faire une redirection après un traitement de formulaire!!
            return $this->redirectToRoute("blog_read", ["id" => $post->getId()]);
        }
        return $this->render("read.html.twig", [
            "post" => $post,
            "form" => $form->createView(),
        ]);
    }
}