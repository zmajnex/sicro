<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use App\Form\CrawlerFormType;

class MainController extends AbstractController
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        //$html = "<h1>Home page</h1>";
        //return new Response($html);
        //$logger->info('I just got the logger');
        return $this->render('home/index.html.twig');
    }
    /**
     * @Route("/custom/{name?}", name="Custom page")
     */
    public function custom(Request $request)
    {
        /**
         * Symfony dumper
         */
        // dump($request);
        $name = $request->get('name');
        //$this->logger->info($name);
        //$this->logger->info(dump($name));
        isset($name) ? $name = $name : $name = 'Mr X';
        //return new Response('Welcome '.$name);
        return $this->render('home/custom.html.twig', [
            'name' => $name,
        ]);
    }
   
}
