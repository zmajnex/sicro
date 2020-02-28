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
class ResultsController extends AbstractController
{
    public $crawler;

    public function __construct(CrawlerController $crawler ){
       
        $this->crawler=$crawler;
    }
    /**
     * @Route("results", name="results")
     */
    public function index(Request $request)
    {
     
        $url = $request->request->get('crawler_form')['url'];
        $this->crawler->crawlUrl($url);
        $resultsTitle = $this->crawler->calculateLinksTitleScore();
        $missingTitles = $this->crawler->missingTitles;
        $metaDescription = $this->crawler->calculateMetaDescription();
        $titleLength = $this->crawler->calculateTitleLength();
        $missingAlts = $this->crawler->calculateImagesScore();
        $imagesWitNoAltTags = $this->crawler->missingImagesAlt;
        return $this->render('form/results.html.twig', array(
            'url' => $url,
            'titles'=> $resultsTitle,
            'missingTitles'=>$missingTitles,
            'metaDescription'=> $metaDescription, 
            'titleLength' => $titleLength,
            'missingAlts' => $missingAlts,
            'imagesWitNoAltTags' => $imagesWitNoAltTags
        ));

    }
  
}