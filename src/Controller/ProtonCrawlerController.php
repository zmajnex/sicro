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
            'url' => $testData));
    }
    




    public function crawlUrl($url){
        /**
 * crawl web page and extract all <a> tag hrefs
 *
 * @param string $url
 *
 * @return array $results
 */
   
        $client = new Client();
        $crawler = $client->request('GET', $url);    
        $fp = fopen('results'.time().'.json','w'); 
        /**
         * Anonymous Simfony function
         * iterate trough $nodes, and write links in json 
         * @return  array $links
         */
       //$links = new \stdClass();
       
       $links = array(
           'url'=>$url,
           'author'=>null,
           'visited'=>null,
           'text'=>null,
           'title'=>null,
       );
       // Get href from a tag
       $results = $crawler->filter('a')->each(function ($node,$i) use ($fp,$links) {
             
              
             $links['url']= $node->link()->getUri();
             $links['author'] = 'Proton';
                 
         fwrite($fp, json_encode($links));
           //return $links['url']; 
        });
        // Get text from a tag
        $text = $crawler->filter('a')->each(function ($node,$i) use ($fp,$links) {
             
            $links['text']= $node->text();
           fwrite($fp, json_encode($links)); 
        });
        // Get title from a tag    
        $title = $crawler->filter('a[title]')->each(function ($node,$i) use ($fp,$links) {
             
            $links['title']= $node->text();
             fwrite($fp, json_encode($links)); 
        });
         fclose($fp);       
        return $url;
    }
}
