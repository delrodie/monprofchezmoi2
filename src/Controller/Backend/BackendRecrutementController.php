<?php

namespace App\Controller\Backend;

use App\Entity\Recrutement;
use App\Form\RecrutementType;
use App\Repository\RecrutementRepository;
use App\Utilities\GestionMedia;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/recrutement")
 */
class BackendRecrutementController extends AbstractController
{
	const menu = "recrutement";
	const sub_menu = "recrutement";
	
	private $gestionMedia;
	
	public function __construct(GestionMedia $gestionMedia)
	{
		$this->gestionMedia = $gestionMedia;
	}
	
    /**
     * @Route("/", name="backend_recrutement_index", methods={"GET"})
     */
    public function index(RecrutementRepository $recrutementRepository): Response
    {
        return $this->render('backend_recrutement/index.html.twig', [
            'recrutements' => $recrutementRepository->findAll(),
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/new", name="backend_recrutement_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recrutement = new Recrutement();
        $form = $this->createForm(RecrutementType::class, $recrutement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	
	        // slug
	        $slugify = new Slugify();
	        $slug = $slugify->slugify($recrutement->getTitre());
	        $recrutement->setSlug($slug);
	
	        $mediaFile = $form->get('media')->getData(); //dd($mediaFile);
	
	        if ($mediaFile){
		        $media = $this->gestionMedia->upload($mediaFile, 'recrutement'); //dd($activite->getLogo());
		
		        // Supression de l'ancien fichier
		        //$this->gestionMedia->removeUpload($activite->getLogo(), 'activite');
		
		        $recrutement->setMedia($media);
	        }
	        // Resumé du contenu de la présentation
	        $resume = substr(strip_tags($recrutement->getContenu()), 0, 155);
	        $recrutement->setResume($resume);
			
            $entityManager->persist($recrutement);
            $entityManager->flush();
	
	        $this->addFlash('success', "Le recrutement a bien été enregistré");

            return $this->redirectToRoute('backend_recrutement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_recrutement/new.html.twig', [
            'recrutement' => $recrutement,
            'form' => $form,
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_recrutement_show", methods={"GET"})
     */
    public function show(Recrutement $recrutement): Response
    {
        return $this->render('backend_recrutement/show.html.twig', [
            'recrutement' => $recrutement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_recrutement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Recrutement $recrutement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecrutementType::class, $recrutement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	
	        // slug
	        $slugify = new Slugify();
	        $slug = $slugify->slugify($recrutement->getTitre());
	        $recrutement->setSlug($slug);
	
	        $mediaFile = $form->get('media')->getData(); //dd($mediaFile);
	
	        if ($mediaFile){
		        $media = $this->gestionMedia->upload($mediaFile, 'recrutement'); //dd($activite->getLogo());
		
		        // Supression de l'ancien fichier
		        $this->gestionMedia->removeUpload($recrutement->getMedia(), 'recrutement');
		
		        $recrutement->setMedia($media);
	        }
	        // Resumé du contenu de la présentation
	        $resume = substr(strip_tags($recrutement->getContenu()), 0, 155);
	        $recrutement->setResume($resume);
			
            $entityManager->flush();
	
	        $this->addFlash('success', "Le recrutement a bien été modifié");

            return $this->redirectToRoute('backend_recrutement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_recrutement/edit.html.twig', [
            'recrutement' => $recrutement,
            'form' => $form,
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_recrutement_delete", methods={"POST"})
     */
    public function delete(Request $request, Recrutement $recrutement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recrutement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($recrutement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_recrutement_index', [], Response::HTTP_SEE_OTHER);
    }
}
