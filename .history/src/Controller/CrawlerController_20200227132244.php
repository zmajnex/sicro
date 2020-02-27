<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

//use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

use GuzzleHttp\Client; 
class CrawlerController extends AbstractController
{
 
    public $currentLinks;
    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function crawlUrl($url)
    {
       
        /**
         * crawl web page and extract all <a> tag hrefs
         *
         * @param string $url
         *
         * @return array $results
         */
        
        $client = new Client();
        $links = array();
        $response = $client->request('GET', $url);
        $statusCode = $response->getStatusCode();
        $html = $response->getBody()->getContents();
        
        // Need to pass $url to constructor
        $crawler = new Crawler($html,$url);
        
        $text = $crawler->filter('a')->text();
        $href = $crawler->filter('a')->link()->getUri();
       // $title = $crawler->filter('a[title]')->eq(0)->text();
       // var_dump($text,$href);die;
       $currentLinks = [];

       // get the links
       $crawler->filter('a')->each(function(Crawler $node, $i) use (&$currentLinks) {
           // get the href
           $nodeUrl = $node->attr('href');
           $nodeName = $node->text();
           $nodeTitle = $node->attr('title');
           $currentLinks[$nodeUrl]['url'] = $nodeUrl;
           $currentLinks[$nodeUrl]['name'] = $nodeName;
           $currentLinks[$nodeUrl]['title'] = $nodeTitle;                   
       });
       //var_dump($currentLinks);die;
       // Show url without tile tag
       foreach($currentLinks as $key){
           if($key['title'] == null){
               
               echo $key['url'];
               echo '<br>';
           }
       }  
       die;
      return $this->currentLinks = $currentLinks;
           
    }
    public function calculateSeoScore(){
         $numberOfLinks = count($this->currentLinks);
         $countTitles = 0 ;
        foreach($this->currentLinks as $link){
           $title = $link['title'];
           isset($title) ? $countTitles++ : $countTitles;
        }
        $percentOfTitles = ($countTitles / $numberOfLinks) * 100;
        return $percentOfTitles ." %";
        // dump($this->currentLinks);
        // die;
    }
}
