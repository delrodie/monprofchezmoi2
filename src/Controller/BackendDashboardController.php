<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend")
 */
class BackendDashboardController extends AbstractController
{
    const menu = "dashboard";
    const sub_menu = "backend";

    /**
     * @Route("/", name="backend_dashboard")
     */
    public function index(): Response
    {
        return $this->render('backend_dashboard/index.html.twig', [
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }
}
