<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use App\Form\CrawlerFormType;

class FormController extends AbstractController
{
     /**
     * @Route("/crawl", name="form page")
     */
    public function new(Request $request)
    {
        $form = $this->createForm(CrawlerFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $url = $form->getData();
            dump($url);
            die();
            return $this->redirectToRoute('form page');
        }
        return $this->render('form/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
