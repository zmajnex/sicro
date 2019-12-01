<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
class ProtonCrawlerController extends AbstractController
{
    /**
     * @Route("/proton/crawler", name="proton_crawler")
     */
    public function index(Request $request)
    {
        dump($request);
        // TO do handle this request
        $url = $request->get('crawler_form[enter_your_url]');
        //$url = $_POST("crawler_form[enter_your_url]");
        dump($url);
        return new Response('Crawled: '. $url);
    }
}
