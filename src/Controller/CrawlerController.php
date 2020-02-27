<?php

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;

//use Goutte\Client;
use Symfony\Component\HttpFoundation\Response;

class CrawlerController extends AbstractController
{

    public $currentLinks;
    public $missingTitles;
    public $metaTitle;
    public $metaDescription;
    public $currentImages;
    public $missingImagesAlt;
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
        $crawler = new Crawler($html, $url);

        $text = $crawler->filter('a')->text();
        $href = $crawler->filter('a')->link()->getUri();
        // $title = $crawler->filter('a[title]')->eq(0)->text();
        // var_dump($text,$href);die;
        $currentLinks = [];

        // get the links
        $crawler->filter('a')->each(function (Crawler $node, $i) use (&$currentLinks) {
            // get the href
            $nodeUrl = $node->attr('href');
            $nodeName = $node->text();
            $nodeTitle = $node->attr('title');
            $currentLinks[$nodeUrl]['url'] = $nodeUrl;
            $currentLinks[$nodeUrl]['name'] = $nodeName;
            $currentLinks[$nodeUrl]['title'] = $nodeTitle;
        });
        foreach ($currentLinks as $key) {
            if ($key['title'] == null) {
                $this->missingTitles[] = $url . $key['url'];
            }
        }
        $this->metaDescription = $crawler->filter('meta[name="description"]')->eq(0)->attr('content');
        $this->metaTitle = $crawler->filter('title')->text();
        // Get alt tags from images
        $currentImages = [];
        $crawler->filter('img')->each(function (Crawler $node, $i) use (&$currentImages) {
            $nodeImageSrc = $node->attr('src');
            $nodeImageAlt = $node->attr('alt');
            $currentImages[$nodeImageSrc]['src'] = $nodeImageSrc;
            $currentImages[$nodeImageSrc]['alt'] = $nodeImageAlt;
        });
        foreach ($currentImages as $key) {

            if ($key['alt'] == null) {
                $this->missingImagesAlt[] = $url . $key['alt'];
            }
        }
        $this->currentImages = $currentImages;
        return $this->currentLinks = $currentLinks;

    }
    public function calculateSeoScore()
    {
        $numberOfLinks = count($this->currentLinks);
        $countTitles = 0;
        foreach ($this->currentLinks as $link) {
            $title = $link['title'];
            isset($title) ? $countTitles++ : $countTitles;
        }
        $percentOfTitles = ($countTitles / $numberOfLinks) * 100;
        return $percentOfTitles . " %";
    }
    public function calculateImagesScore()
    {
        $numberOfImages = count($this->currentImages);
        $countAlt = 0;
        foreach ($this->currentImages as $link) {
            $alt= $link['alt'];
            isset($alt) ? $countAlt++ : $countAlt;
        }
        $percentOfAlts = ($countAlt / $numberOfImages) * 100;
        return $percentOfAlts . " %";
    }
    public function getMissingTitles()
    {
        return $this->missingTitles;
    }
    public function getMissingImagesAlt()
    {
        return $this->missingImagesAlt;
    }
    public function calculateMetaDescription()
    {
        $tmp = $this->metaDescription;
        $metaDescriptionLength = strlen($tmp);
        if ($metaDescriptionLength > 160) {
            return "Your meta description is too long";
        } else {
            return "Your meta description length is fine!";
        }
    }
    public function calculateTitleLength()
    {
        $tmp = $this->metaTitle;
        $metaTitleLength = strlen($tmp);
        if ($metaTitleLength > 65) {
            return "Your title is too long";
        } else {
            return "Your title length is fine!";
        }
    }
}
