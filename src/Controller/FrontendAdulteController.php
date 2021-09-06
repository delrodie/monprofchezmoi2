<?php

namespace App\Controller;

use App\Entity\Adulte;
use App\Entity\MenuAdulte;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cours-adulte")
 */
class FrontendAdulteController extends AbstractController
{
    /**
     * @Route("/", name="frontend_adulte_menu")
     */
    public function index(Request $request): Response
    {
        $slug = $request->get('slug');
        $menu = $this->getDoctrine()->getRepository(MenuAdulte::class)->findByVar($slug); //dd($menu);

        if ($menu) return $this->redirectToRoute('frontend_adulte_show',['slug'=>$menu->getSlug()]);
        else return $this->redirectToRoute('frontend_erreur',['slug'=>'404']);
    }

    /**
     * @Route("/{slug}/", name="frontend_adulte_show", methods={"GET"})
     */
    public function show(Adulte $adulte)
    {
        return $this->render('frontend_adulte/show.html.twig',[
            'adulte' => $adulte,
            'menu' => 'adulte'
        ]);
    }
}
