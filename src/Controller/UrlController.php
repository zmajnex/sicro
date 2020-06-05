<?php

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\UrlHelper;


class UrlController extends AbstractController
{

 public $url;
    
    /**
     * Set Url
     *
     * @return string url
     */
    public function setUrl($url){
        
            return $this->url = $url;
       
    }
    /**
     * 
     */
    public function getUrl(){
        return $this->url;
    }
   
}
