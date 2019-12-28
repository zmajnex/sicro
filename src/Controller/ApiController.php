<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use App\Form\CrawlerFormType;

class ApiController extends AbstractController
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
// Create API
    /**
     * @Route("/api/users", name="api")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index()
    {
        $users = [
            [
                "name" => "Luke Skywalker",
                "height" => "172",
                "mass" => "77",
                "hair_color" => "blond",
                "skin_color" => "fair",
                "eye_color" => "blue",
                "birth_year" => "19BBY",
                "gender" => "male",
            ],
            [
                "name" => "C-3PO",
                "height" => "167",
                "mass" => "75",
                "hair_color" => "n/a",
                "skin_color" => "gold",
                "eye_color" => "yellow",
                "birth_year" => "112BBY",
                "gender" => "n/a",
            ],
            [
                "name" => "R2-D2",
                "height" => "96",
                "mass" => "32",
                "hair_color" => "n/a",
                "skin_color" => "white, blue",
                "eye_color" => "red",
                "birth_year" => "33BBY",
                "gender" => "n/a",
            ],
            [
                "name" => "Darth Vader",
                "height"=> "202",
                "mass"=> "136",
                "hair_color"=> "none",
                "skin_color"=> "white",
                "eye_color"=> "yellow",
                "birth_year"=> "41.9BBY",
                "gender"=> "male",
            ],
            [
                "name" => "Leia Organa",
                "height" => "150",
                "mass" => "49",
                "hair_color" => "brown",
                "skin_color" => "light",
                "eye_color" => "brown",
                "birth_year" => "19BBY",
                "gender" => "female",
            ]
        ];
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        $response->setContent(json_encode($users));

        return $response;
    }
  
   
   
}
