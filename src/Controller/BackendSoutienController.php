<?php

namespace App\Controller;

use App\Entity\Soutien;
use App\Form\SoutienType;
use App\Repository\SoutienRepository;
use App\Utilities\GestionMedia;
use App\Utilities\Utility;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/soutien")
 */
class BackendSoutienController extends AbstractController
{
    const menu = "soutien";
    const sub_menu = "soutien";

    private $gestionMedia;
    private $utility;

    public function __construct(GestionMedia $gestionMedia, Utility  $utility)
    {
        $this->gestionMedia = $gestionMedia;
        $this->utility = $utility;
    }

    /**
     * @Route("/", name="backend_soutien_index", methods={"GET"})
     */
    public function index(SoutienRepository $soutienRepository): Response
    {
        return $this->render('backend_soutien/index.html.twig', [
            'soutiens' => $soutienRepository->findAll(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/new", name="backend_soutien_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $soutien = new Soutien();
        $form = $this->createForm(SoutienType::class, $soutien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Verification de non attribution du numéro d'affichage
            $affichage = $this->getDoctrine()->getRepository(Soutien::class)->findOneBy(['affichage'=>$soutien->getAffichage()]);
            if ($affichage){
                $this->addFlash('danger', "Attention ce numéro d'affichage a déjà été attribué au soutien ".$affichage->getTitre());
                return $this->redirectToRoute('backend_soutien_new');
            }

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($soutien->getTitre());
            $soutien->setSlug($slug);

            $mediaFile = $form->get('media')->getData(); //dd($mediaFile);

            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'soutien'); //dd($activite->getLogo());

                // Supression de l'ancien fichier
                //$this->gestionMedia->removeUpload($activite->getLogo(), 'activite');

                $soutien->setMedia($media);
            }
            // Resumé du contenu de la présentation
            //$resume = substr(strip_tags(), 0, 155);
            $resume = $this->utility->resume($soutien->getContenu());
            $soutien->setResume($resume);

            $entityManager->persist($soutien);
            $entityManager->flush();

            $this->addFlash('success', "Le soutien ". $soutien->getTitre()." a bien été enregistré");

            return $this->redirectToRoute('backend_soutien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_soutien/new.html.twig', [
            'soutien' => $soutien,
            'form' => $form,
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_soutien_show", methods={"GET"})
     */
    public function show(Soutien $soutien): Response
    {
        return $this->render('backend_soutien/show.html.twig', [
            'soutien' => $soutien,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_soutien_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Soutien $soutien): Response
    {
        $form = $this->createForm(SoutienType::class, $soutien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($soutien->getTitre());
            $soutien->setSlug($slug);

            $mediaFile = $form->get('media')->getData(); //dd($mediaFile);

            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'soutien'); //dd($activite->getLogo());

                // Supression de l'ancien fichier
                $this->gestionMedia->removeUpload($soutien->getMedia(), 'soutien');

                $soutien->setMedia($media);
            }
            // Resumé du contenu de la présentation
            $resume = $this->utility->resume($soutien->getContenu());
            $soutien->setResume($resume);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "Le soutien ". $soutien->getTitre()." a bien été modifié");

            return $this->redirectToRoute('backend_soutien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_soutien/edit.html.twig', [
            'soutien' => $soutien,
            'form' => $form,
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_soutien_delete", methods={"POST"})
     */
    public function delete(Request $request, Soutien $soutien): Response
    {
        if ($this->isCsrfTokenValid('delete'.$soutien->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $media = $soutien->getMedia();
            $entityManager->remove($soutien);
            $entityManager->flush();

            // Supression de l'ancien fichier
            $this->gestionMedia->removeUpload($media, 'soutien');

            $this->addFlash('success', "Soutien a été supprimé avec succès");
        }

        return $this->redirectToRoute('backend_soutien_index', [], Response::HTTP_SEE_OTHER);
    }
}
