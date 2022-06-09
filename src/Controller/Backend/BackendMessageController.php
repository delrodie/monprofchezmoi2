<?php

namespace App\Controller\Backend;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Utilities\GestionMedia;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backend/message')]
class BackendMessageController extends AbstractController
{
	const menu = "newsletter";
	const sub_menu = "message";
	
	private GestionMedia $gestionMedia;
	
	public function __construct(GestionMedia $gestionMedia)
	{
		$this->gestionMedia = $gestionMedia;
	}
	
    #[Route('/', name: 'app_backend_message_index', methods: ['GET'])]
    public function index(MessageRepository $messageRepository): Response
    {
        return $this->render('backend_message/index.html.twig', [
            'messages' => $messageRepository->findBy([],['id'=>'DESC']),
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    #[Route('/new', name: 'app_backend_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MessageRepository $messageRepository): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			
	        $mediaFile = $form->get('media')->getData(); //dd($mediaFile);
	
	        if ($mediaFile){
		        $media = $this->gestionMedia->upload($mediaFile, 'message'); //dd($activite->getLogo());
		
		        // Supression de l'ancien fichier
		        //$this->gestionMedia->removeUpload($activite->getLogo(), 'activite');
		
		        $message->setMedia($media);
	        }
			
            $messageRepository->add($message);
			
			$this->addFlash('success', $message->getObjet()." a enregistré avec succès!");
			
            return $this->redirectToRoute('app_backend_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_message/new.html.twig', [
            'message' => $message,
            'form' => $form,
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    #[Route('/{id}', name: 'app_backend_message_show', methods: ['GET'])]
    public function show(Message $message): Response
    {
        return $this->render('backend_message/show.html.twig', [
            'message' => $message,
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Message $message, MessageRepository $messageRepository): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	
	
	        $mediaFile = $form->get('media')->getData(); //dd($mediaFile);
	
	        if ($mediaFile){
		        $media = $this->gestionMedia->upload($mediaFile, 'message'); //dd($activite->getLogo());
		
		        // Supression de l'ancien fichier
		        if ($message->getMedia())
		            $this->gestionMedia->removeUpload($message->getMedia(), 'message');
		
		        $message->setMedia($media);
	        }
			
            $messageRepository->add($message);
            return $this->redirectToRoute('app_backend_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_message/edit.html.twig', [
            'message' => $message,
            'form' => $form,
	        'menu' => self::menu,
	        'sub_menu' => self::sub_menu
        ]);
    }

    #[Route('/{id}', name: 'app_backend_message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $message, MessageRepository $messageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $messageRepository->remove($message);
        }

        return $this->redirectToRoute('app_backend_message_index', [], Response::HTTP_SEE_OTHER);
    }
}
