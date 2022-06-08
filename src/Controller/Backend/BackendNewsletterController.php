<?php

namespace App\Controller\Backend;

use App\Entity\Newsletter;
use App\Form\NewsletterType;
use App\Repository\NewsletterRepository;
use App\Utilities\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backend/newsletter')]
class BackendNewsletterController extends AbstractController
{
	const menu = "newsletter";
	const sub_menu = "newsletter";
	
	private Utility $utility;
	
	public function __construct(Utility $utility)
	{
		$this->utility = $utility;
	}
	
    #[Route('/', name: 'app_backend_newsletter_index', methods: ['GET'])]
    public function index(NewsletterRepository $newsletterRepository): Response
    {
        return $this->render('backend_newsletter/index.html.twig', [
            'newsletters' => $newsletterRepository->findAll(),
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    #[Route('/new', name: 'app_backend_newsletter_new', methods: ['GET', 'POST'])]
    public function new(Request $request, NewsletterRepository $newsletterRepository): Response
    {
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			
			if ($this->utility->verifEmail($newsletter)){
				$this->addFlash('danger', $newsletter->getEmail()." existe déjà. ");
				return $this->redirectToRoute('app_backend_newsletter_new',[], Response::HTTP_SEE_OTHER);
			}
			
			$newsletter->setStatut(true);
            $newsletterRepository->add($newsletter);
			
			$this->addFlash('success', $newsletter->getEmail()." a bien été ajouté a la liste des newsletter");
            return $this->redirectToRoute('app_backend_newsletter_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_newsletter/new.html.twig', [
            'newsletter' => $newsletter,
            'form' => $form,
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    #[Route('/{id}', name: 'app_backend_newsletter_show', methods: ['GET'])]
    public function show(Newsletter $newsletter): Response
    {
        return $this->render('backend_newsletter/show.html.twig', [
            'newsletter' => $newsletter,
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_newsletter_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Newsletter $newsletter, NewsletterRepository $newsletterRepository): Response
    {
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newsletterRepository->add($newsletter);
			
			$this->addFlash('success', $newsletter->getEmail(). " a été modifié avec succès!");
            return $this->redirectToRoute('app_backend_newsletter_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_newsletter/edit.html.twig', [
            'newsletter' => $newsletter,
            'form' => $form,
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    #[Route('/{id}', name: 'app_backend_newsletter_delete', methods: ['POST'])]
    public function delete(Request $request, Newsletter $newsletter, NewsletterRepository $newsletterRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$newsletter->getId(), $request->request->get('_token'))) {
            $newsletterRepository->remove($newsletter);
			
			$this->addFlash('success', $newsletter->getEmail(). " a été supprimé avec succès!");
        }

        return $this->redirectToRoute('app_backend_newsletter_index', [], Response::HTTP_SEE_OTHER);
    }
}
