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

class ResultsController extends AbstractController
{
    public $crawler;
    
    

    public function __construct(CrawlerController $crawler ){
       
        $this->crawler = $crawler;
       
    }
    /**
     * @Route("results", name="results")
     */
    public function index(Request $request, LoggerInterface $logger )
    {
     
       $url = $request->request->get('crawler_form')['url'];
       $this->crawler->setUrl($url);
       if($this->crawler->isCrawable()){
        $this->crawler->crawlUrl();
        $this->crawler->getLinks();
        $this->crawler->getImages();
        $this->crawler->getMetaDesription();
        $this->crawler->getTitle();
        $brokenLinks = $this->crawler->showBrokenLinks();
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
            'hasMetaDescription' => $this->crawler->hasMetaDescription,
            'notAcceptable' => $this->crawler->notAcceptable,
            'h1' => $this->crawler->h1,
            'h2' => $this->crawler->h2,
            'h3' => $this->crawler->h3,
            'h4' => $this->crawler->h4,
            'h5' => $this->crawler->h5,
            'h6' => $this->crawler->h6,
           // 'robots'=>$this->crawler->checkRobots(),
            'brokenLinks' => $brokenLinks 
        ));

    }
    else {
        $flush ='The page or website is not crawable, please try later';
        $statusCode = $this->crawler->statusCode;
        
        return $this->render('form/error.html.twig', array('flush'=>$flush,'statusCode' => $statusCode));
} 

}
}
