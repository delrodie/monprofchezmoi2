<?php

namespace App\Controller\Backend;

use App\Entity\Candidat;
use App\Form\CandidatType;
use App\Repository\CandidatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/candidat")
 */
class BackendCandidatController extends AbstractController
{
    /**
     * @Route("/", name="backend_candidat_index", methods={"GET"})
     */
    public function index(CandidatRepository $candidatRepository): Response
    {
        return $this->render('backend_candidat/index.html.twig', [
            'candidats' => $candidatRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="backend_candidat_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $candidat = new Candidat();
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($candidat);
            $entityManager->flush();

            return $this->redirectToRoute('backend_candidat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_candidat/new.html.twig', [
            'candidat' => $candidat,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="backend_candidat_show", methods={"GET"})
     */
    public function show(Candidat $candidat): Response
    {
        return $this->render('backend_candidat/show.html.twig', [
            'candidat' => $candidat,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_candidat_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Candidat $candidat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('backend_candidat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_candidat/edit.html.twig', [
            'candidat' => $candidat,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="backend_candidat_delete", methods={"POST"})
     */
    public function delete(Request $request, Candidat $candidat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$candidat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($candidat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_candidat_index', [], Response::HTTP_SEE_OTHER);
    }
}
