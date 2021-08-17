<?php

namespace App\Controller\Backend;

use App\Entity\Presentation;
use App\Form\PresentationType;
use App\Repository\PresentationRepository;
use App\Utilities\GestionMedia;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/presentation")
 */
class BackendPresentationController extends AbstractController
{
    const menu = "gestion";
    const sub_menu = "presentation";

    private $gestionMedia;

    public function __construct(GestionMedia $gestionMedia)
    {
        $this->gestionMedia = $gestionMedia;
    }

    /**
     * @Route("/", name="backend_presentation_index", methods={"GET"})
     */
    public function index(PresentationRepository $presentationRepository): Response
    {
        return $this->render('backend_presentation/index.html.twig', [
            'presentations' => $presentationRepository->findAll(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/new", name="backend_presentation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $presentation = new Presentation();
        $form = $this->createForm(PresentationType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($presentation->getTitre());
            $presentation->setSlug($slug);

            $mediaFile = $form->get('media')->getData(); //dd($mediaFile);

            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'presentation'); //dd($activite->getLogo());

                // Supression de l'ancien fichier
                //$this->gestionMedia->removeUpload($activite->getLogo(), 'activite');

                $presentation->setMedia($media);
            }
            // Resumé du contenu de la présentation
            $resume = substr(strip_tags($presentation->getContenu()), 0, 155);
            $presentation->setResume($resume);

            $entityManager->persist($presentation);
            $entityManager->flush();

            $this->addFlash('success', "La presentation a bien été enregistrée");

            return $this->redirectToRoute('backend_presentation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_presentation/new.html.twig', [
            'presentation' => $presentation,
            'form' => $form,
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_presentation_show", methods={"GET"})
     */
    public function show(Presentation $presentation): Response
    {
        return $this->render('backend_presentation/show.html.twig', [
            'presentation' => $presentation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_presentation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Presentation $presentation): Response
    {
        $form = $this->createForm(PresentationType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($presentation->getTitre());
            $presentation->setSlug($slug);

            $mediaFile = $form->get('media')->getData(); //dd($mediaFile);

            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'presentation'); //dd($activite->getLogo());

                // Supression de l'ancien fichier
                $this->gestionMedia->removeUpload($presentation->getMedia(), 'presentation');

                $presentation->setMedia($media);
            }
            // Resumé du contenu de la présentation
            $resume = substr(strip_tags($presentation->getContenu()), 0, 155);
            $presentation->setResume($resume);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "La présentation a bien été modifiée");

            return $this->redirectToRoute('backend_presentation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_presentation/edit.html.twig', [
            'presentation' => $presentation,
            'form' => $form,
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_presentation_delete", methods={"POST"})
     */
    public function delete(Request $request, Presentation $presentation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$presentation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $media = $presentation->getMedia();
            $entityManager->remove($presentation);
            $entityManager->flush();

            // Supression de l'ancien fichier
            $this->gestionMedia->removeUpload($media, 'presentation');

            $this->addFlash('success', "Presentation supprimée avec succès");
        }

        return $this->redirectToRoute('backend_presentation_index', [], Response::HTTP_SEE_OTHER);
    }
}
