<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 100; $i++){
            $post = new Post();
            $post->setTitle("post". $i);
            $post->setContent("contenu". $i);
            $manager->persist($post);

            for($j =1; $j <= rand(1, 10) ; $j++){
                $comment = new Comment();
                $comment->setAuthor("auteur ". $i);
                $comment->setContent("commentaire ". $j);
                $comment->setPost($post);
                $manager->persist($comment);
            }
        }
        $manager->flush();
    }
}
