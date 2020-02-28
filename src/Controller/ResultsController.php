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
        $metaDescription = $this->crawler->calculateMetaDescription();
        $titleLength = $this->crawler->calculateTitleLength();
        $missingAlts = $this->crawler->calculateImagesScore();
        return $this->render('form/results.html.twig', array(
            'url' => $url,
            'titles'=> $resultsTitle,
            'missingTitles'=>$this->crawler->missingTitles,
            'metaDescription'=> $metaDescription, 
            'titleLength' => $titleLength,
            'missingAlts' => $missingAlts,
            'imagesWitNoAltTags' => $this->crawler->missingImagesAlt,
            'hasLinks' => $this->crawler->numberOfLinks,
            'hasImages' => $this->crawler->numberOfImages,
            'hasTitle' => $this->crawler->hasTitle,
            'hasMetaDescription' => $this->crawler->hasMetaDescription

        ));

    }
  
}
