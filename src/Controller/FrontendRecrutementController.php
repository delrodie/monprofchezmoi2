<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Form\CandidatType;
use App\Repository\RecrutementRepository;
use App\Utilities\GestionMedia;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recrutement")
 */
class FrontendRecrutementController extends AbstractController
{
	private $gestionMedia;
	
	public function __construct(GestionMedia $gestionMedia)
	{
		$this->gestionMedia = $gestionMedia;
	}
	
    /**
     * @Route("/", name="frontend_recrutement_index", methods={"GET","POST"})
     */
    public function index(RecrutementRepository $recrutementRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
	    $candidat = new Candidat();
	    $form = $this->createForm(CandidatType::class, $candidat);
	    $form->handleRequest($request);
	
	    if ($form->isSubmitted() && $form->isValid()) {
		
		    $mediaFile = $form->get('cv')->getData(); //dd($mediaFile);
		    $mediaFile2 = $form->get('lettreMotivation')->getData();
		
		    if ($mediaFile){
			    $media = $this->gestionMedia->upload($mediaFile, 'cv'); //dd($activite->getLogo());
						
			    $candidat->setCv($media);
		    }
		
		    if ($mediaFile2){
			    $media2 = $this->gestionMedia->upload($mediaFile2, 'lettre'); //dd($activite->getLogo());
						
			    $candidat->setLettreMotivation($media2);
		    }
			
		    $entityManager->persist($candidat);
		    $entityManager->flush();
		
		    return $this->redirectToRoute('frontend_recrutement_sucess', [], Response::HTTP_SEE_OTHER);
	    }
	
	    return $this->renderForm('frontend_recrutement/index.html.twig', [
            'recrutement' => $recrutementRepository->findOneBy([],['id'=>"DESC"]),
	        'menu' => 'recrutement',
		    'candidat' => $candidat,
		    'form' => $form,
        ]);
    }
	
	/**
	 * @Route("/succes", name="frontend_recrutement_sucess")
	 */
	public function candidat(RecrutementRepository $recrutementRepository): Response
	{
		return $this->render('frontend_recrutement/candidat.html.twig',[
			'recrutement' => $recrutementRepository->findOneBy([],['id'=>"DESC"]),
			'menu' => 'recrutement',
		]);
	}
}
