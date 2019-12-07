<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use App\Form\CrawlerFormType;
use App\Controller\ProtonCrawlerController;
/**
 * This class render the form and setting up action method 
 */
class FormController extends AbstractController
{
    /**
     * @Route("/crawl", name="form page")
     */
    public function new(Request $request)
    {
        $form = $this->createForm(CrawlerFormType::class, null, [
            'action' => $this->generateUrl('proton_crawler'),
            'method' => 'POST',
        ]);

        return $this->render('form/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
   
}
