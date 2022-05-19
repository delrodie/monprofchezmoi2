<?php

namespace App\Controller;

use App\Entity\Coordonnee;
use App\Form\CoordonneeType;
use App\Repository\CoordonneeRepository;
use App\Utilities\GestionMedia;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backend/coordonnee')]
class BackendCoordonneeController extends AbstractController
{
	const menu = "gestion";
	const sub_menu = "coordonnee";
	
	private $gestionMedia;
	
	public function __construct(GestionMedia $gestionMedia)
	{
		$this->gestionMedia = $gestionMedia;
	}
	
    #[Route('/', name: 'app_backend_coordonnee_index', methods: ['GET'])]
    public function index(CoordonneeRepository $coordonneeRepository): Response
    {
		$exist = $coordonneeRepository->findOneBy([],['id'=>'DESC']);
		if (!$exist){
			return $this->redirectToRoute('app_backend_coordonnee_new');
		}else{
			return $this->redirectToRoute('app_backend_coordonnee_show',['id'=>$exist->getId()]);
		}
    
    }

    #[Route('/new', name: 'app_backend_coordonnee_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CoordonneeRepository $coordonneeRepository): Response
    {
        $coordonnee = new Coordonnee();
        $form = $this->createForm(CoordonneeType::class, $coordonnee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coordonneeRepository->add($coordonnee);
            return $this->redirectToRoute('app_backend_coordonnee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_coordonnee/new.html.twig', [
            'coordonnee' => $coordonnee,
            'form' => $form,
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    #[Route('/{id}', name: 'app_backend_coordonnee_show', methods: ['GET'])]
    public function show(Coordonnee $coordonnee): Response
    {
        return $this->render('backend_coordonnee/show.html.twig', [
            'coordonnee' => $coordonnee,
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_coordonnee_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Coordonnee $coordonnee, CoordonneeRepository $coordonneeRepository): Response
    {
        $form = $this->createForm(CoordonneeType::class, $coordonnee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coordonneeRepository->add($coordonnee);
            return $this->redirectToRoute('app_backend_coordonnee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_coordonnee/edit.html.twig', [
            'coordonnee' => $coordonnee,
            'form' => $form,
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    #[Route('/{id}', name: 'app_backend_coordonnee_delete', methods: ['POST'])]
    public function delete(Request $request, Coordonnee $coordonnee, CoordonneeRepository $coordonneeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coordonnee->getId(), $request->request->get('_token'))) {
            $coordonneeRepository->remove($coordonnee);
        }

        return $this->redirectToRoute('app_backend_coordonnee_index', [], Response::HTTP_SEE_OTHER);
    }
}
