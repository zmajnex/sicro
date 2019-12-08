<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
//use App\Controller\CrawlerController;
//use GuzzleHttp\Client; 
class ResultsController extends AbstractController
{
 
    /**
     * @Route("/results", name="results")
     */
    public function index(Request $request)
    {

        $url = $request->request->get('crawler_form')['url'];
       // $crawler = new \App\Controller\CrawlerController();
      //$r=$crawler->crawlUrl($url);
     // dump($re);
        return $this->render('form/results.html.twig', array(
            'url' => $url
        ));
    }
  
}
