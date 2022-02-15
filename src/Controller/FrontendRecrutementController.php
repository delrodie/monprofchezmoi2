<?php

namespace App\Controller;

use App\Repository\RecrutementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontendRecrutementController extends AbstractController
{
    /**
     * @Route("/recrutement", name="frontend_recrutement_index")
     */
    public function index(RecrutementRepository $recrutementRepository): Response
    {
        return $this->render('frontend_recrutement/index.html.twig', [
            'recrutement' => $recrutementRepository->findOneBy([],['id'=>"DESC"]),
	        'menu' => 'recrutement'
        ]);
    }
}
