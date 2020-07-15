<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use App\Form\SerpFormType;

/**
 * This class render the form and setting up action method 
 */
class SerpFormController extends AbstractController
{
    /**
     * @Route("/SERP", name="serp form")
     */
    public function new(Request $request)
    {
        $form = $this->createForm(SerpFormType::class, null, [
            'action' => $this->generateUrl('serpresults'),
            'method' => 'POST',
            
        ]);

        return $this->render('serp/serpform.html.twig', [
            'serpForm' => $form->createView(),
        ]);
    }
   
}
