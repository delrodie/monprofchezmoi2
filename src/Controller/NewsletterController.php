<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Newsletter;
use App\Repository\MessageRepository;
use App\Repository\NewsletterRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/newsletter')]
class NewsletterController extends AbstractController
{
	private MailerInterface $mailer;
	private NewsletterRepository $newsletterRepository;
	private MessageRepository $messageRepository;
	
	public function __construct(MailerInterface $mailer, NewsletterRepository $newsletterRepository, MessageRepository $messageRepository)
	{
		$this->mailer = $mailer;
		$this->newsletterRepository = $newsletterRepository;
		$this->messageRepository = $messageRepository;
	}
	
	/**
	 * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
	 */
	#[Route('/', name: 'app_newsletter')]
    public function index(): Response
    {
        return $this->render('newsletter/message.html.twig', [
            'controller_name' => 'NewsletterController',
        ]);
    }
	
	/**
	 * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
	 */
	#[Route('/{id}', name:'app_newsletter_message')]
	public function message(Message $message)
	{ //$this->adresses();
		$email = (New TemplatedEmail())
			->from(new Address('noreply@monprofchezmoi.ci', 'MONPROFCHEZMOI'))
			->replyTo('info@monprofchezmoi.ci')
			->to('delrodieamoikon@gmail.com')
			//->bcc(...$this->adresses())
			->subject($message->getObjet())
			->htmlTemplate('newsletter/mail.html.twig')
			->context([
				'expiration_date' => new \DateTime('+7 days'),
				'username' => 'MONPROFCHEZMOI',
				'message' => $message
			])
		;
		
		$this->mailer->send($email);
		
		// Mise a jour des message
		//$date =
		$message->setEnvoiAt(date('Y-m-d H:i:s'));
		$this->messageRepository->add($message, true);
		
		$this->addFlash('success', "Votre message a bien été envoyé");
		
		return $this->redirectToRoute('app_backend_message_show',['id' => $message->getId()]);
	}
	
	#[Route('/{id}/apercu', name:'app_newsletter_message_apercu')]
	public function apercu(Message $message): Response
	{
		return $this->render('newsletter/mail.html.twig',[
			'message' => $message
		]);
	}
	
	public function adresses(): array
	{
		$adresses = $this->newsletterRepository->findBy(['statut'=>true]); //dd($adresses);
		
		$list=[]; $i=0;
		foreach ($adresses as $adress)	{
			$list[$i++]=new Address($adress->getEmail());
			//$list[$i++]=new Address($adress->getEmail(), $adress->getNom());
		}
		
		return $list;
	}
}
