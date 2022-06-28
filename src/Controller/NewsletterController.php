<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Newsletter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/newsletter')]
class NewsletterController extends AbstractController
{
	private MailerInterface $mailer;
	
	public function __construct(MailerInterface $mailer)
	{
		$this->mailer = $mailer;
	}
	
	/**
	 * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
	 */
	#[Route('/', name: 'app_newsletter')]
    public function index(): Response
    {
		$email = (New Email())
			->from('delrodieamoikon@gmail.com')
			->to('delrodieamoikon@gmail.com')
			->subject('Test')
			->text('Tesy')
			->html('html')
				;
		
		$this->mailer->send($email);
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
