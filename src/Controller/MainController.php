<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
         //$html = "<h1>Home page</h1>";
         //return new Response($html);
        return $this->render('home/index.html.twig');
    }
    /**
     * @Route("/custom/{name?}", name="Custom page")
     */
    public function custom(Request $request){
        // dump($request);
        $name = $request->get('name');
        isset($name) ? $name = $name : $name = 'Mr X';
       //return new Response('Welcome '.$name);
       return $this->render('home/custom.html.twig',[
           'name' => $name
       ]);
    }
}
