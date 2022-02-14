<?php

namespace App\Controller;

use App\Entity\Adulte;
use App\Entity\MenuAdulte;
use App\Utilities\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cours-adulte")
 */
class FrontendAdulteController extends AbstractController
{
	private $utility;
	
	public function __construct(Utility $utility)
	{
		$this->utility = $utility;
	}
	
    /**
     * @Route("/", name="frontend_adulte_menu")
     */
    public function index(Request $request): Response
    {
        $adultes = $this->getDoctrine()->getRepository(Adulte::class)->findAll();
		$menus = $this->getDoctrine()->getRepository(MenuAdulte::class)->findAll(); //dd($menus);
		return $this->render('frontend_adulte/index.html.twig',[
			'adultes' => $this->utility->menuAdulte()
		]);
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
