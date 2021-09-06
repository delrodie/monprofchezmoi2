<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/erreur")
 */
class FrontendErreurController extends AbstractController
{
    /**
     * @Route("/{slug}", name="frontend_erreur")
     */
    public function index($slug): Response
    {
        if ($slug === '404'){
            $erreur['titre'] = 'Page non trouvé';
            $erreur['code'] = '404';
        }else{
            $erreur['titre'] = 'Page non trouvé';
            $erreur['code'] = '404';
        }
        return $this->render('frontend_erreur/index.html.twig', [
            'erreur' => $erreur,
            'menu' => 'erreur'
        ]);
    }
}
