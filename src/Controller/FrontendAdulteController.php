<?php

namespace App\Controller;

use App\Entity\Adulte;
use App\Entity\Contact;
use App\Entity\MenuAdulte;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Utilities\Utility;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cours-adulte")
 */
class FrontendAdulteController extends AbstractController
{
	private $utility;
	private $contactRepository;
	private $em;
	
	public function __construct(Utility $utility, ContactRepository $contactRepository, EntityManagerInterface $em)
	{
		$this->utility = $utility;
		$this->contactRepository = $contactRepository;
		$this->em = $em;
	}
	
    /**
     * @Route("/", name="frontend_adulte_menu")
     */
    public function index(Request $request): Response
    {
        $adultes = $this->getDoctrine()->getRepository(Adulte::class)->findAll();
		$menus = $this->getDoctrine()->getRepository(MenuAdulte::class)->findAll(); //dd($menus);
		return $this->render('frontend_adulte/index.html.twig',[
			'adultes' => $this->utility->menuAdulte()
		]);
    }

    /**
     * @Route("/{slug}/", name="frontend_adulte_show", methods={"GET","POST"})
     */
    public function show(Request $request, Adulte $adulte)
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
		
        return $this->render('frontend_adulte/show.html.twig',[
            'adulte' => $adulte,
            'menu' => 'adulte'
        ]);
    }
}
