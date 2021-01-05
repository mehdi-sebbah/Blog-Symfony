<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class TestController extends AbstractController
{
    /**
     * @Route("/hello")
     */
    public function testHello(Request $request){
        dump($request);
        
    }
}