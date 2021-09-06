<?php

namespace App\Controller;

use App\Entity\Soutien;
use App\Repository\SoutienRepository;
use App\Utilities\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/soutien")
 */
class FrontendSoutienController extends AbstractController
{
    private $utility;

    public function __construct(Utility $utility)
    {
        $this->utility = $utility;
    }

    /**
     * @Route("/", name="frontend_soutien_menu")
     */
    public function index(): Response
    {
        return $this->render('frontend_soutien/index.html.twig', [
            'soutiens' => $this->utility->soutien(),
        ]);
    }

    /**
     * @Route("/{slug}", name="frontend_soutien_show", methods={"GET"})
     */
    public function show(Soutien $soutien, SoutienRepository  $soutienRepository): Response
    {
        $affichage = $soutien->getAffichage();
        $prec = 6; $suiv = 1;
        if ($affichage === 1){
            $prec = 6;
            $suiv = $affichage + 1;
        }elseif ($affichage === 6){
            $prec = $affichage - 1;
            $suiv = 1;
        }else{
            $prec = $affichage - 1;
            $suiv = $affichage + 1;
        }

        return $this->render('frontend_soutien/show.html.twig',[
            'soutien' => $soutien,
            'precedent' =>$soutienRepository->findOneBy(['affichage'=>$prec]),
            'suivant' => $soutienRepository->findOneBy(['affichage'=>$suiv]),
        ]);
    }
}
