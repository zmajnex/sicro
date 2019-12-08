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
        $crawler = new Crawler($html);
        $text = $crawler->filter('a')->eq(0)->text();
        //$href = $crawler->filter('a')->attr('href')->eq(0);
        //$title = $crawler->filter('a')->attr('title')->eq(0)->text();
        var_dump($href);die;
        foreach ($crawler as $domElement) {
         var_dump($domElement->nodeName);
     }
     die;
        //  $crawler = $client->request('GET', $url);

      //   $results = $crawler->filter('a');
      //   $fp = fopen('results' . time() . '.json', 'w');
      //   /**
      //    * Anonymous Simfony function
      //    * iterate trough $nodes, and write links in json 
      //    * @return  array $links
      //    */
      //   //$links = new \stdClass();
      
      //   // Get href from a tag
       
      //   $results = $crawler->filter('a')->each(function ($node, $i) use ($fp, $links) {
      //        $href = $node->link()->getUri();
      //        $text = $node->text();       
      //       $links['url'] = $href;
      //       $links['author'] = 'Proton';
      //       $links['text'] = $text;
           
      //      fwrite($fp, json_encode($links));                      
      //   });
      //      // Get title from a tag    
      //      $title = $crawler->filter('a[title]')->each(function ($node, $i) use ($fp, $links) {
      //      $title =  $node->text();
      //       $links['title'] = $title; 
                     
      //      fwrite($fp, json_encode($links));
        
      //   });
       
      //  // return $links;
      
      //   fclose($fp);
       // return $url;
    }
}
