<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
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
        return $url;
    }
}
