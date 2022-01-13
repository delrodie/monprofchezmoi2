<?php

namespace App\Controller;

use App\Entity\Niveau;
use App\Form\NiveauType;
use App\Repository\NiveauRepository;
use App\Utilities\Utility;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/niveau")
 */
class BackendNiveauController extends AbstractController
{
	const menu = "recherche";
	const sub_menu = "niveau";
	
	private $utility;
	
	public function __construct(Utility $utility)
	{
		$this->utility = $utility;
	}
	
    /**
     * @Route("/", name="backend_niveau_index", methods={"GET"})
     */
    public function index(NiveauRepository $niveauRepository): Response
    {
        return $this->render('backend_niveau/index.html.twig', [
            'niveaux' => $niveauRepository->findAll(),
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/new", name="backend_niveau_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $niveau = new Niveau();
        $form = $this->createForm(NiveauType::class, $niveau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	        //$slugify
	        $slug = $this->utility->slug($niveau->getType().'-'.$niveau->getLibelle());
	        $niveau->setSlug($slug);
			
			// Verification
	        if ($this->verification($slug))
				return $this->redirectToRoute('backend_niveau_index');
			
            $entityManager->persist($niveau);
            $entityManager->flush();
			
			$this->addFlash('success', $niveau->getLibelle()." a été ajouté avec succès");

            return $this->redirectToRoute('backend_niveau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_niveau/new.html.twig', [
            'niveau' => $niveau,
            'form' => $form,
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_niveau_show", methods={"GET"})
     */
    public function show(Niveau $niveau): Response
    {
        return $this->render('backend_niveau/show.html.twig', [
            'niveau' => $niveau,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_niveau_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Niveau $niveau, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NiveauType::class, $niveau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			
	        //$slugify
	        $slug = $this->utility->slug($niveau->getType().'-'.$niveau->getLibelle());
	        $niveau->setSlug($slug);
			
            $entityManager->flush();
	
	        $this->addFlash('success', $niveau->getLibelle()." a été modifié avec succès");

            return $this->redirectToRoute('backend_niveau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_niveau/edit.html.twig', [
            'niveau' => $niveau,
            'form' => $form,
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_niveau_delete", methods={"POST"})
     */
    public function delete(Request $request, Niveau $niveau, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$niveau->getId(), $request->request->get('_token'))) {
            $entityManager->remove($niveau);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_niveau_index', [], Response::HTTP_SEE_OTHER);
    }
	
	/**
	 * @param $str
	 * @return bool
	 */
	function verification($str): bool
	{
		$verif = $this->getDoctrine()->getRepository(Niveau::class)->findOneBy(['slug'=>$str]);
		if ($verif){
			$this->addFlash('danger', 'Échec ce niveau existe déjà');
			return true;
		}
		else return false;
	}
}
