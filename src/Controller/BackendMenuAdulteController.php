<?php

namespace App\Controller;

use App\Entity\MenuAdulte;
use App\Form\MenuAdulteType;
use App\Repository\MenuAdulteRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/menu/adulte")
 */
class BackendMenuAdulteController extends AbstractController
{
    const menu = "adulte";
    const sub_menu = "menu";

    /**
     * @Route("/", name="backend_menu_adulte_index", methods={"GET"})
     */
    public function index(MenuAdulteRepository $menuAdulteRepository): Response
    {
        return $this->render('backend_menu_adulte/index.html.twig', [
            'menu_adultes' => $menuAdulteRepository->findAll(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/new", name="backend_menu_adulte_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $menuAdulte = new MenuAdulte();
        $form = $this->createForm(MenuAdulteType::class, $menuAdulte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($menuAdulte->getTitre());
            $menuAdulte->setSlug($slug);

            $menuAdulte->setStatut(true);

            $entityManager->persist($menuAdulte);
            $entityManager->flush();

            $this->addFlash('success', "Le menu ". $menuAdulte->getTitre()." a bien été enregistré");

            return $this->redirectToRoute('backend_menu_adulte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_menu_adulte/new.html.twig', [
            'menu_adulte' => $menuAdulte,
            'form' => $form,
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_menu_adulte_show", methods={"GET"})
     */
    public function show(MenuAdulte $menuAdulte): Response
    {
        return $this->render('backend_menu_adulte/show.html.twig', [
            'menu_adulte' => $menuAdulte,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_menu_adulte_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MenuAdulte $menuAdulte): Response
    {
        $form = $this->createForm(MenuAdulteType::class, $menuAdulte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($menuAdulte->getTitre());
            $menuAdulte->setSlug($slug);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "Le menu ". $menuAdulte->getTitre()." a bien été modifié");

            return $this->redirectToRoute('backend_menu_adulte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_menu_adulte/edit.html.twig', [
            'menu_adulte' => $menuAdulte,
            'form' => $form,
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_menu_adulte_delete", methods={"POST"})
     */
    public function delete(Request $request, MenuAdulte $menuAdulte): Response
    {
        if ($this->isCsrfTokenValid('delete'.$menuAdulte->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($menuAdulte);
            $entityManager->flush();

            $this->addFlash('success', "Le menu ". $menuAdulte->getTitre()." a bien été supprimé");
        }

        return $this->redirectToRoute('backend_menu_adulte_index', [], Response::HTTP_SEE_OTHER);
    }
}
