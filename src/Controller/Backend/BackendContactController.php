<?php

namespace App\Controller\Backend;

use App\Entity\Contact;
use App\Form\Contact1Type;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/contact")
 */
class BackendContactController extends AbstractController
{
	const menu = "contact";
	const sub_menu = "contact";
	
    /**
     * @Route("/", name="backend_contact_index", methods={"GET"})
     */
    public function index(ContactRepository $contactRepository): Response
    {
        return $this->render('backend_contact/index.html.twig', [
            'contacts' => $contactRepository->findBy([],['createdAt' => "DESC"]),
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/new", name="backend_contact_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(Contact1Type::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            return $this->redirectToRoute('backend_contact_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_contact/new.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="backend_contact_show", methods={"GET"})
     */
    public function show(Contact $contact): Response
    {
        return $this->render('backend_contact/show.html.twig', [
            'contact' => $contact,
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_contact_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Contact $contact, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Contact1Type::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('backend_contact_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_contact/edit.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="backend_contact_delete", methods={"POST"})
     */
    public function delete(Request $request, Contact $contact, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $entityManager->remove($contact);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_contact_index', [], Response::HTTP_SEE_OTHER);
    }
}
