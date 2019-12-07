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
//use GuzzleHttp\Client; 
class ProtonCrawlerController extends AbstractController
{
   


    /**
     * @Route("/proton/crawler", name="proton_crawler")
     */
    public function index(Request $request)
    {

        $url = $request->request->get('crawler_form')['url'];
        $testData = $this->crawlUrl($url);
        // dump($url);
        // return new Response('Crawling: '. $url . '...');
        return $this->render('form/results.html.twig', array(
            'url' => $testData
        ));
    }


  /**
   * TO DO : Fix json, check if url is already crawled
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
        $crawler = $client->request('GET', $url);
        $fp = fopen('results' . time() . '.json', 'w');
        /**
         * Anonymous Simfony function
         * iterate trough $nodes, and write links in json 
         * @return  array $links
         */
        //$links = new \stdClass();
      
        // Get href from a tag
        $results = $crawler->filter('a')->each(function ($node, $i) use ($fp, $links) {
             $href = $node->link()->getUri();
             $text = $node->text();
            
            $links['url'] = $href;
            $links['author'] = 'Proton';
            $links['text'] = $text;
           fwrite($fp, json_encode($links));
            //return $links; 
        });
      //  return $links;
        
           // Get title from a tag    
           $title = $crawler->filter('a[title]')->each(function ($node, $i) use ($fp, $links) {
           $title =  $node->text();
            $links['title'] = $title;           
            fwrite($fp, json_encode($links));
            
        });
       // return $links;
       // var_dump($links);
        fclose($fp);
        return $url;
    }
}
