<?php

namespace App\Controller\Backend;

use App\Entity\Media;
use App\Form\MediaType;
use App\Repository\MediaRepository;
use App\Utilities\GestionMedia;
use App\Utilities\Utility;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/media")
 */
class BackendMediaController extends AbstractController
{
	const menu = "gestion";
	const sub_menu = "media";
	
	private $gestionMedia;
	private $utility;
	
	public function __construct(GestionMedia $gestionMedia, Utility  $utility)
	{
		$this->gestionMedia = $gestionMedia;
		$this->utility = $utility;
	}
	
    /**
     * @Route("/", name="backend_media_index", methods={"GET"})
     */
    public function index(MediaRepository $mediaRepository): Response
    {
        return $this->render('backend_media/index.html.twig', [
            'medias' => $mediaRepository->findAll(),
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/new", name="backend_media_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $medium = new Media();
        $form = $this->createForm(MediaType::class, $medium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	        // slug
	        $slugify = new Slugify();
	        $slug = $slugify->slugify($medium->getTitre());
	        $medium->setSlug($slug);
	
	        $mediaFile = $form->get('fichier')->getData(); //dd($mediaFile);
	
	        if ($mediaFile){
		        $media = $this->gestionMedia->upload($mediaFile, 'media'); //dd($activite->getLogo());
		
		        // Supression de l'ancien fichier
		        //$this->gestionMedia->removeUpload($activite->getLogo(), 'activite');
		
		        $medium->setFichier($media);
	        }
            $entityManager->persist($medium);
            $entityManager->flush();
	
	        $this->addFlash('success', "Le media ". $medium->getTitre()." a bien été enregistré");
	
	
	        return $this->redirectToRoute('backend_media_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_media/new.html.twig', [
            'medium' => $medium,
            'form' => $form,
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_media_show", methods={"GET"})
     */
    public function show(Media $medium): Response
    {
        return $this->render('backend_media/show.html.twig', [
            'medium' => $medium,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_media_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Media $medium, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MediaType::class, $medium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	        // slug
	        $slugify = new Slugify();
	        $slug = $slugify->slugify($medium->getTitre());
	        $medium->setSlug($slug);
	
	        $mediaFile = $form->get('fichier')->getData(); //dd($mediaFile);
	
	        if ($mediaFile){
		        $media = $this->gestionMedia->upload($mediaFile, 'media'); //dd($activite->getLogo());
		
		        // Supression de l'ancien fichier
		        $this->gestionMedia->removeUpload($medium->getFichier(), 'media');
		
		        $medium->setFichier($media);
	        }
			
            $entityManager->flush();
	
	        $this->addFlash('success', "Le media ". $medium->getTitre()." a bien été modifié");

            return $this->redirectToRoute('backend_media_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_media/edit.html.twig', [
            'medium' => $medium,
            'form' => $form,
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_media_delete", methods={"POST"})
     */
    public function delete(Request $request, Media $medium, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$medium->getId(), $request->request->get('_token'))) {
	        $media = $medium->getFichier();
	        $titre = $medium->getTitre();
            $entityManager->remove($medium);
            $entityManager->flush();
	
	        // Supression de l'ancien fichier
	        $this->gestionMedia->removeUpload($media, 'media');
	
	        $this->addFlash('success', "Le media ". $titre." a bien été supprimé");
        }

        return $this->redirectToRoute('backend_media_index', [], Response::HTTP_SEE_OTHER);
    }
}
