<?php

namespace App\Controller;

use App\Entity\Adulte;
use App\Form\AdulteType;
use App\Repository\AdulteRepository;
use App\Utilities\GestionMedia;
use App\Utilities\Utility;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/adulte")
 */
class BackendAdulteController extends AbstractController
{
    const menu = "adulte";
    const sub_menu = "contenu";

    private $gestionMedia;
    private $utility;

    public function __construct(GestionMedia $gestionMedia, Utility  $utility)
    {
        $this->gestionMedia = $gestionMedia;
        $this->utility = $utility;
    }

    /**
     * @Route("/", name="backend_adulte_index", methods={"GET"})
     */
    public function index(AdulteRepository $adulteRepository): Response
    {
        return $this->render('backend_adulte/index.html.twig', [
            'adultes' => $adulteRepository->findAll(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/new", name="backend_adulte_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $adulte = new Adulte();
        $form = $this->createForm(AdulteType::class, $adulte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($adulte->getTitre());
            $adulte->setSlug($slug);

            $mediaFile = $form->get('media')->getData(); //dd($mediaFile);

            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'adulte'); //dd($activite->getLogo());

                // Supression de l'ancien fichier
                //$this->gestionMedia->removeUpload($activite->getLogo(), 'activite');

                $adulte->setMedia($media);
            }
            // Resumé du contenu de la présentation
            //$resume = substr(strip_tags(), 0, 155);
            $resume = $this->utility->resume($adulte->getContenu());
            $adulte->setResume($resume);
            
            $entityManager->persist($adulte);
            $entityManager->flush();

            $this->addFlash('success', "Le cours adulte ". $adulte->getTitre()." a bien été enregistré");

            return $this->redirectToRoute('backend_adulte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_adulte/new.html.twig', [
            'adulte' => $adulte,
            'form' => $form,
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_adulte_show", methods={"GET"})
     */
    public function show(Adulte $adulte): Response
    {
        return $this->render('backend_adulte/show.html.twig', [
            'adulte' => $adulte,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_adulte_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Adulte $adulte): Response
    {
        $form = $this->createForm(AdulteType::class, $adulte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($adulte->getTitre());
            $adulte->setSlug($slug);

            $mediaFile = $form->get('media')->getData(); //dd($mediaFile);

            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'adulte'); //dd($activite->getLogo());

                // Supression de l'ancien fichier
                $this->gestionMedia->removeUpload($adulte->getMedia(), 'adulte');

                $adulte->setMedia($media);
            }
            // Resumé du contenu de la présentation
            //$resume = substr(strip_tags(), 0, 155);
            $resume = $this->utility->resume($adulte->getContenu());
            $adulte->setResume($resume);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "Le cours adulte ". $adulte->getTitre()." a bien été modifié");

            return $this->redirectToRoute('backend_adulte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_adulte/edit.html.twig', [
            'adulte' => $adulte,
            'form' => $form,
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_adulte_delete", methods={"POST"})
     */
    public function delete(Request $request, Adulte $adulte): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adulte->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $media = $adulte->getMedia();
            $entityManager->remove($adulte);
            $entityManager->flush();

            // Supression de l'ancien fichier
            $this->gestionMedia->removeUpload($media, 'adulte');

            $this->addFlash('success', "Adulte a été supprimé avec succès");
        }

        return $this->redirectToRoute('backend_adulte_index', [], Response::HTTP_SEE_OTHER);
    }
}
