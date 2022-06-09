<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Newsletter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/newsletter')]
class NewsletterController extends AbstractController
{
    #[Route('/', name: 'app_newsletter')]
    public function index(): Response
    {
        return $this->render('newsletter/message.html.twig', [
            'controller_name' => 'NewsletterController',
        ]);
    }
	
	#[Route('/{id}', name:'app_newsletter_message')]
	public function message(Message $message)
	{
		return $this->render('newsletter/mail.html.twig',[
			'message' => $message
		]);
	}
}
