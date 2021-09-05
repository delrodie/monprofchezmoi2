<?php

namespace App\Controller;

use App\Entity\Cover;
use App\Form\CoverType;
use App\Repository\CoverRepository;
use App\Utilities\GestionMedia;
use App\Utilities\Utility;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/cover")
 */
class BackendCoverController extends AbstractController
{
    const menu = "gestion";
    const sub_menu = "cover";

    private $gestionMedia;
    private $utility;

    public function __construct(GestionMedia $gestionMedia, Utility  $utility)
    {
        $this->gestionMedia = $gestionMedia;
        $this->utility = $utility;
    }

    /**
     * @Route("/", name="backend_cover_index", methods={"GET"})
     */
    public function index(CoverRepository $coverRepository): Response
    {
        return $this->render('backend_cover/index.html.twig', [
            'covers' => $coverRepository->findAll(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/new", name="backend_cover_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cover = new Cover();
        $form = $this->createForm(CoverType::class, $cover);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($cover->getTitre());
            $cover->setSlug($slug);

            $mediaFile = $form->get('media')->getData(); //dd($mediaFile);

            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'cover'); //dd($activite->getLogo());

                // Supression de l'ancien fichier
                //$this->gestionMedia->removeUpload($activite->getLogo(), 'activite');

                $cover->setMedia($media);
            }
            
            $entityManager->persist($cover);
            $entityManager->flush();

            $this->addFlash('success', "La cover ". $cover->getTitre()." a bien été enregistrée");

            return $this->redirectToRoute('backend_cover_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_cover/new.html.twig', [
            'cover' => $cover,
            'form' => $form,
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_cover_show", methods={"GET"})
     */
    public function show(Cover $cover): Response
    {
        return $this->render('backend_cover/show.html.twig', [
            'cover' => $cover,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_cover_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cover $cover): Response
    {
        $form = $this->createForm(CoverType::class, $cover);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($cover->getTitre());
            $cover->setSlug($slug);

            $mediaFile = $form->get('media')->getData(); //dd($mediaFile);

            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'cover'); //dd($activite->getLogo());

                // Supression de l'ancien fichier
                $this->gestionMedia->removeUpload($cover->getMedia(), 'cover');

                $cover->setMedia($media);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "La cover ". $cover->getTitre()." a bien été modifiée");

            return $this->redirectToRoute('backend_cover_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_cover/edit.html.twig', [
            'cover' => $cover,
            'form' => $form,
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_cover_delete", methods={"POST"})
     */
    public function delete(Request $request, Cover $cover): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cover->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $media = $cover->getMedia();
            $titre = $cover->getTitre();
            $entityManager->remove($cover);
            $entityManager->flush();

            // Supression de l'ancien fichier
            $this->gestionMedia->removeUpload($media, 'cover');

            $this->addFlash('success', "La cover ". $titre." a bien été supprimée");
        }

        return $this->redirectToRoute('backend_cover_index', [], Response::HTTP_SEE_OTHER);
    }
}
