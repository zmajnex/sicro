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
use App\Controller\CrawlerController;
use App\Controller\Core\Title;
use App\Controller\UrlController;
use Psr\Log\LoggerInterface;

class SerpResultController extends AbstractController
{
  //  public $crawler;
    
    

    public function __construct(CrawlerController $crawler ){
       
       // $this->crawler = $crawler;
       
    }
    /**
     * @Route("serpresults", name="serpresults")
     */
    public function index(Request $request, LoggerInterface $logger )
    {
     
       $keyWord = $request->request->get('serp_form')['keywords'];
       $numberOfResults = $request->request->get('serp_form')['number_of_results'];
       var_dump($keyWord,$numberOfResults);die;
       // TODO get meta tiles and meta description for n results
       
}
}