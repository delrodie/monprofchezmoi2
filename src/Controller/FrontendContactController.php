<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contact")
 */
class FrontendContactController extends AbstractController
{
	private $contactRepository;
	private $em;
	
	public function __construct(ContactRepository $contactRepository, EntityManagerInterface $em)
	{
		$this->contactRepository = $contactRepository;
		$this->em = $em;
	}
	
    /**
     * @Route("/", name="frontend_contact_index")
     */
    public function index(Request $request): Response
    {
		$contact = new Contact();
	    $form = $this->createForm(ContactType::class, $contact);
	    $form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()){
			//dd($contact);
			$this->em->persist($contact);
			$this->em->flush();
			
			return $this->redirectToRoute('frontend_contact_message', ['id'=>$contact->getId()]);
		}
		
        return $this->renderForm('frontend_contact/index.html.twig', [
            'contact' => $contact,
	        'form' => $form,
	        'menu' => 'contact'
        ]);
    }
	
	/**
	 * @Route("/{id}", name="frontend_contact_message", methods={"GET"})
	 */
	public function message(Contact $contact)
	{
		return $this->render('frontend_contact/message.html.twig',[
			'contact' => $contact,
			'menu' => "contact"
		]);
	}
}
