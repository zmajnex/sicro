<?php

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\UrlHelper;

class CrawlerController extends AbstractController
{

    public $currentLinks;
    public $missingTitles;
    public $metaTitle;
    public $metaDescription;
    public $currentImages;
    public $missingImagesAlt;
    public $numberOfLinks;
    public $numberOfImages;
    public $hasTitle;
    public $hasMetaDescription;
    public const MAX_TITLE_LENGTH = 65;
    public const MAX_META_DESCRIPTION_LENGTH = 160;
    private $crawler;
    private $url;
    public $notAcceptable;
    public $h1;
    public $h2;
    public $h3;
    public $h4;
    public $h5;
    public $h6;

    /**
     * Crawl given url, extract page title, meta description,
     * links, alt and title tags.
     *
     * @param string $url
     *
     * @return
     */

    public function crawlUrl($url)
    {

        $client = new Client();
        $links = array();
        $response = $client->request('GET', $url);
        $statusCode = $response->getStatusCode();
        if ($statusCode == 406) {
            $this->notAcceptable = true;
        };
        $html = $response->getBody()->getContents();
        $crawler = new Crawler($html, $url);
        $this->numberOfLinks = $crawler->filter('a')->count();
        $this->numberOfImages = $crawler->filter('img')->count();
        $this->hasMetaDescription = $crawler->filter('meta[name="description"]')->count();
        $this->hasTitle = $crawler->filter('title')->count();
        $this->url = $url;
        $this->h1 = $crawler->filter('h1')->count();
        $this->h2 = $crawler->filter('h2')->count();
        $this->h3 = $crawler->filter('h3')->count();
        $this->h4 = $crawler->filter('h4')->count();
        $this->h5 = $crawler->filter('h5')->count();
        $this->h6 = $crawler->filter('h6')->count();
        return $this->crawler = $crawler;
    }
    /**
     * Extract title tag, name and href from links
     *
     * @return $currentLinks
     */
    public function getLinks()
    {
        if ($this->numberOfLinks > 0) {
            $text = $this->crawler->filter('a')->text();
            $href = $this->crawler->filter('a')->link()->getUri();
            $currentLinks = [];
            $this->crawler->filter('a')->each(function (Crawler $node, $i) use (&$currentLinks) {
                $nodeUrl = $node->filter('a')->link()->getUri();
                $nodeName = $node->text();
                $nodeTitle = $node->attr('title');
                $currentLinks[$nodeUrl]['url'] = $nodeUrl;
                $currentLinks[$nodeUrl]['name'] = $nodeName;
                $currentLinks[$nodeUrl]['title'] = $nodeTitle;
            });
            foreach ($currentLinks as $key) {
                if ($key['title'] == null) {
                    $this->missingTitles[] =  $key['url'];
                }
            }
            
            $this->currentLinks = $currentLinks;

        }

        return $this->currentLinks;
    }
    /**
     * Extract src, alt tags from images
     *
     * @return $currentImages
     */
    public function getImages()
    {
        if ($this->numberOfImages > 0) {
            $currentImages = [];
            $this->crawler->filter('img')->each(function (Crawler $node, $i) use (&$currentImages) {
                $nodeImageSrc = $node->attr('src');
                $nodeImageAlt = $node->attr('alt');
                $currentImages[$nodeImageSrc]['src'] = $nodeImageSrc;
                $currentImages[$nodeImageSrc]['alt'] = $nodeImageAlt;
            });
            foreach ($currentImages as $key) {

                if ($key['alt'] == null) {
                       $tmp = strpos( $key['src'],$this->url );
                       if(is_int($tmp) === false){
                        $this->missingImagesAlt[] = $this->url .$key['src'];
                       }else{
                        $this->missingImagesAlt[] =  $key['src'];
                       }
                }
            }
            $this->currentImages = $currentImages;
        }
        return $this->currentImages;
    }
    /**
     * Get page meta description
     *
     * @return $metaDescription
     */
    public function getMetaDesription()
    {
        if ($this->hasMetaDescription > 0) {
            $this->metaDescription = $this->crawler->filter('meta[name="description"]')->eq(0)->attr('content');
        }
        return $this->metaDescription;
    }
    /**
     * Get page title
     *
     * @return $metaTitle
     */
    public function getTitle()
    {
        if ($this->hasTitle > 0) {
            $this->metaTitle = $this->crawler->filter('title')->text();
        }
        return $this->metaTitle;
    }

    /**
     * Calculate link titles score form 0 to 100 %
     *
     * @return string
     */

    public function calculateLinksTitleScore()
    {
        if ($this->currentLinks) {
            $numberOfLinks = count($this->currentLinks);
            $countTitles = 0;
            foreach ($this->currentLinks as $link) {
                $title = $link['title'];
                isset($title) ? $countTitles++ : $countTitles;
            }
            $percentOfTitles = ($countTitles / $numberOfLinks) * 100;
            return $percentOfTitles . " %";
        } else {
            return 'Page has no title';
        }
    }
    /**
     * Calculate images alt tag score form 0 to 100 %
     *
     * @return string
     */
    public function calculateImagesScore()
    {
        if ($this->currentImages) {
            $numberOfImages = count($this->currentImages);
            $countAlt = 0;
            foreach ($this->currentImages as $link) {
                $alt = $link['alt'];
                isset($alt) ? $countAlt++ : $countAlt;
            }
            $percentOfAlts = ($countAlt / $numberOfImages) * 100;
            return $percentOfAlts . " %";
        } else {
            return "The page has no images";
        }
    }
    /**
     * Calculate meta description length
     *
     * @return string
     */
    public function calculateMetaDescription()
    {
        $tmp = $this->metaDescription;
        $metaDescriptionLength = strlen($tmp);
        if ($metaDescriptionLength > self::MAX_META_DESCRIPTION_LENGTH) {
            return "Your meta description is too long";
        } elseif ($metaDescriptionLength == null) {
            return "Your page has no meta description";
        } else {
            return "Your meta description length is fine!";
        }
    }
    /**
     * Calculate title length
     *
     * @return string
     */
    public function calculateTitleLength()
    {
        $tmp = $this->metaTitle;
        $metaTitleLength = strlen($tmp);
        if ($metaTitleLength > self::MAX_TITLE_LENGTH) {
            return "Your title is too long";
        } elseif ($metaTitleLength == null) {
            return "Your page has no title";
        } else {
            return "Your title length is fine!";
        }

    }/**
     * To do Refactor code
     *
     * @return array $results
     */
    public function getBrokenLinks()
    {
        $client = HttpClient::create();
        $links = $this->currentLinks;
        $statusCode = [];
        $results = [];
        foreach ($links as $link) {
            if (filter_var($link['url'], FILTER_VALIDATE_URL) && strpos($link['url'],"mailto") !== 0) {
                $response = $client->request('GET', $link['url']);
                $statusCode = $response->getStatusCode();
                $results[] = array(
                    'statusCode' => $statusCode,
                    'url' => $link['url'],
                );
            }

        }
        return $results;
    }
    /**
     * Check if robots.txt is present
     * Works only on home page  - for now
     * @return void
     */
    public function checkRobots(){
        $client = new Client();
        $request = new Request();
        $response = $client->request('GET', $request->getBaseUrl()."/robots.txt");
        $statusCode = $response->getStatusCode();
        if($statusCode == 200){
            return "Robots.txt discoverd";
        } else {
            return "No Robots.txt present";
        }
    }
}
