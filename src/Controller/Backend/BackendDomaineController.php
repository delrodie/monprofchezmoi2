<?php

namespace App\Controller\Backend;

use App\Entity\Domaine;
use App\Form\DomaineType;
use App\Repository\DomaineRepository;
use App\Utilities\Utility;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/domaine")
 */
class BackendDomaineController extends AbstractController
{
	const menu = "recherche";
	const sub_menu = "domaine";
	
	private $utility;
	
	public function __construct(Utility $utility)
	{
		$this->utility = $utility;
	}
	
    /**
     * @Route("/", name="backend_domaine_index", methods={"GET"})
     */
    public function index(DomaineRepository $domaineRepository): Response
    {
        return $this->render('backend_domaine/index.html.twig', [
            'domaines' => $domaineRepository->findAll(),
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/new", name="backend_domaine_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $domaine = new Domaine();
        $form = $this->createForm(DomaineType::class, $domaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			//$slugify
	        $slug = $this->utility->slug($domaine->getType().'-'.$domaine->getLibelle());
	        $domaine->setSlug($slug);
			
			// Vérification
			if ($this->verification($slug))
				return $this->redirectToRoute('backend_domaine_index');
			
            $entityManager->persist($domaine);
            $entityManager->flush();
			
			$this->addFlash('success', $domaine->getLibelle()." a été ajouté avec succès");

            return $this->redirectToRoute('backend_domaine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_domaine/new.html.twig', [
            'domaine' => $domaine,
            'form' => $form,
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_domaine_show", methods={"GET"})
     */
    public function show(Domaine $domaine): Response
    {
        return $this->render('backend_domaine/show.html.twig', [
            'domaine' => $domaine,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_domaine_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Domaine $domaine, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DomaineType::class, $domaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	        //$slugify
	        $slug = $this->utility->slug($domaine->getType().'-'.$domaine->getLibelle());
	        $domaine->setSlug($slug);
			
            $entityManager->flush();
			
			$this->addFlash('success', $domaine->getLibelle().' a été modifié avec succès!');

            return $this->redirectToRoute('backend_domaine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_domaine/edit.html.twig', [
            'domaine' => $domaine,
            'form' => $form,
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_domaine_delete", methods={"POST"})
     */
    public function delete(Request $request, Domaine $domaine, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$domaine->getId(), $request->request->get('_token'))) {
            $entityManager->remove($domaine);
            $entityManager->flush();
			
			$this->addFlash('success', "Le domaine ".$domaine->getLibelle()." a été supprimé avec succès!");
        }

        return $this->redirectToRoute('backend_domaine_index', [], Response::HTTP_SEE_OTHER);
    }
	
	function verification($str): bool
	{
		$verif = $this->getDoctrine()->getRepository(Domaine::class)->findOneBy(['slug'=>$str]);
		if ($verif){
			$this->addFlash('danger', 'Echec ce domaine existe déjà');
			return true;
		}
		else return false;
	}
}
