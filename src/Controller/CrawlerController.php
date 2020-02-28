<?php

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;

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
        $html = $response->getBody()->getContents();
        $crawler = new Crawler($html, $url);
        $this->numberOfLinks = $crawler->filter('a')->count();
        $this->numberOfImages = $crawler->filter('img')->count();
        $this->hasMetaDescription = $crawler->filter('meta[name="description"]')->count();
        $this->hasTitle = $crawler->filter('title')->count();
        
        if ($this->numberOfLinks > 0) {
            $text = $crawler->filter('a')->text();
            $href = $crawler->filter('a')->link()->getUri();
            $currentLinks = [];
            // Get the links, title and name
         
            $crawler->filter('a')->each(function (Crawler $node, $i) use (&$currentLinks) {
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
            $this->currentLinks = $currentLinks;
        }

        // Get alt tags from images
        if ($this->numberOfImages > 0) {
            $currentImages = [];
            $crawler->filter('img')->each(function (Crawler $node, $i) use (&$currentImages) {
                $nodeImageSrc = $node->attr('src');
                $nodeImageAlt = $node->attr('alt');
                $currentImages[$nodeImageSrc]['src'] = $nodeImageSrc;
                $currentImages[$nodeImageSrc]['alt'] = $nodeImageAlt;
            });
            foreach ($currentImages as $key) {

                if ($key['alt'] == null) {
                    $this->missingImagesAlt[] = $url . $key['src'];
                }
            }
            $this->currentImages = $currentImages;
        }
        // Get meta description and page title

        if ($this->hasMetaDescription > 0) {
            $this->metaDescription = $crawler->filter('meta[name="description"]')->eq(0)->attr('content');
        }

        if ($this->hasTitle > 0) {
            $this->metaTitle = $crawler->filter('title')->text();
        }
        return;

    }
    /**
     * Calculate link titles score form 0 to 100 %
     *
     * @return string
     */

    public function calculateLinksTitleScore()
    {if ($this->currentLinks) {
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

    }
}
