<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Uploader\Uploader;
use App\Uploader\UploaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        $limit = $request->get("limit", 10);
        $page = $request->get("page", 1);

        $posts = $this->getDoctrine()->getRepository(Post::class)->getPaginatedPosts(
            $page,
            $limit
        );
        $pages = count($posts) / $limit;

        $range = range(
            max($page - 3, 1),
            min($page + 3, $pages)
        );

        return $this->render("blog/index.html.twig", [
            "posts" => $posts,
            "pages" => $pages,
            "page" => $page,
            "limit" => $limit,
            "range" => $range,
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

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
            //Toujours faire une redirection après un traitement de formulaire!!
            return $this->redirectToRoute("blog_read", ["id" => $post->getId()]);
        }
        return $this->render("blog/read.html.twig", [
            "post" => $post,
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/publier-article", name="blog_create")
     */
    public function create(
        Request $request,
        UploaderInterface $uploader
    ): Response {

        $post = new Post();

        $form = $this->createForm(PostType::class, $post, [
            "validation_groups" => ["Default", "create"]
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get("file")->getData();

            $post->setImage($uploader->upload($file));

            $this->getDoctrine()->getManager()->persist($post);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute("blog_read", ["id" => $post->getId()]);
        }

        return $this->render("blog/create.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifier-article/{id}", name="blog_update")
     */
    public function update(
        Request $request,
        Post $post,
        SluggerInterface $slugger,
        string $uploadsAbsoluteDir,
        string $uploadsRelativeDir
    ): Response {
        $form = $this->createForm(PostType::class, $post)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get("file")->getData();
            if ($file !== null) {
                $filename = sprintf(
                    "%s_%s.%s",
                    $slugger->slug($file->getClientOriginalName()),
                    uniqid(),
                    $file->getClientOriginalExtension()
                );

                $file->move($uploadsAbsoluteDir, $filename);

                $post->setImage($uploadsRelativeDir . "/" . $filename);
            }
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("blog_read", ["id" => $post->getId()]);
        }
        return $this->render("blog/update.html.twig", [
            "form" => $form->createView(),
        ]);
    }
}
