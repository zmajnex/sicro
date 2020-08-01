<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

use GuzzleHttp\Client;
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
    
    public $titles;
    public $descriptions;
    public $urls;

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
       $location = $request->request->get('serp_form')['location'];
       // Personilezed websearch, turned off
       $pws = 0;
       $client = new Client();
       
       $googleUrl = "https://www.google.com/search?q=".$keyWord."&num=".$numberOfResults."&gl=".$location."&pws=".$pws;
       
       $response = $client->request('GET', $googleUrl
      //  ,[
      //    'headers' => [
      //        'X-Crawlera-Cookies' => 'disable',
      //        'Accept-Encoding' => 'gzip, deflate, br'
      //    ]]
      );
     //  $response = $client->getAsync($googleUrl);
       $html = $response->getBody()->getContents();
       $crawler = new Crawler($html, $googleUrl);
       $nodeTitles = $crawler->filter('.vvjwJb');
       //$nodeDescriptions = $crawler->filter('.s3v9rd');
       $nodeDescriptions = $crawler->filter('.BNeawe.s3v9rd.AP7Wnd .BNeawe.s3v9rd.AP7Wnd');
       $nodeBreadcrumbs = $crawler->filter('.BNeawe.UPmit.AP7Wnd');
       $nodeUrls = $crawler->filter('.kCrYT > a');
       //dump($nodeDescriptions);die;
       //dump($this->url);die;
       foreach($nodeTitles as $node) {
         $this->titles[] = $node->nodeValue;
       };
       
       foreach($nodeDescriptions as $description) {
         $this->descriptions[] = $description->nodeValue;
       };
     
      //  return new Response(
      //    '<pre> '.print_r($this->description).'</pre>'
        
      //  ); 
      // return view
      return $this->render('serp/serpresults.html.twig', array(
         'keyword' => $keyWord,
         'descriptions'=> $this->descriptions,
         'titles'=>$this->titles
      ));
}
}