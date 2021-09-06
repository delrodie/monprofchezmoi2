<?php

namespace App\Controller;

use App\Entity\Cover;
use App\Entity\Presentation;
use App\Utilities\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $utility;

    public function __construct(Utility $utility)
    {
        $this->utility = $utility;
    }
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        $cover = $this->getDoctrine()->getRepository(Cover::class)->findOneBy([],['id'=>'DESC']);

        return $this->render('home/index.html.twig', [
            'cover' => $cover,
            'soutiens' => $this->utility->soutien(),
            'presentation' => $this->getDoctrine()->getRepository(Presentation::class)->findOneBy([],['id'=>'DESC'])
        ]);
    }
}
