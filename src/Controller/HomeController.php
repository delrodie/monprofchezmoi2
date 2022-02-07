<?php

namespace App\Controller;

use App\Entity\Cover;
use App\Entity\Domaine;
use App\Entity\Niveau;
use App\Entity\Presentation;
use App\Utilities\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class HomeController extends AbstractController
{
    private $utility;

    public function __construct(Utility $utility)
    {
        $this->utility = $utility;
    }
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
		//return $this->redirectToRoute('app_maintenance');
        $cover = $this->getDoctrine()->getRepository(Cover::class)->findOneBy(['statut'=>true],['id'=>'DESC']);

        return $this->render('home/index.html.twig', [
            'cover' => $cover,
            'soutiens' => $this->utility->soutien(),
            'presentation' => $this->getDoctrine()->getRepository(Presentation::class)->findOneBy([],['id'=>'DESC']),
            'menu' => 'home',
	        'domaines' => $this->utility->domaine()
        ]);
    }

    /**
     * @Route("/pourquoi-nous-choisir", name="frontend_presentation", methods={"GET"})
     */
    public function presentation()
    {

        return $this->render('home/presentation.html.twig',[
            'presentation' => $this->getDoctrine()->getRepository(Presentation::class)->findOneBy([],['id'=>"DESC"]),
            'menu' => 'presentation'
        ]);
    }
	
	/**
	 * @Route("/filtre-section-accueil/", name="frontend_filtre_accueil", methods={"GET","POST"})
	 */
	public function filtre(Request $request): \Symfony\Component\HttpFoundation\JsonResponse
	{
		//Initialisation
		$encoders = [new XmlEncoder(), new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$serializer = new Serializer($normalizers, $encoders);
		
		$requestDomaine = $request->get('value'); //dd($requestDomaine);
		$niveau = [];
		$domaine = $this->getDoctrine()->getRepository(Domaine::class)->findOneBy(['id'=>$requestDomaine]);
		if ($domaine){
			$niveau = $this->getDoctrine()->getRepository(Niveau::class)->findBy(['type'=>$domaine->getType()]);
		}
		
		return $this->json($niveau);
	}
	
	/**
	 * @Route("/maintenance/2022", name="app_maintenance")
	 */
	public function maintenance()
	{
		$cover = $this->getDoctrine()->getRepository(Cover::class)->findOneBy([],['id'=>'DESC']);
		
		return $this->render('home/maintenance.html.twig', [
			'cover' => $cover,
			'soutiens' => $this->utility->soutien(),
			'presentation' => $this->getDoctrine()->getRepository(Presentation::class)->findOneBy([],['id'=>'DESC']),
			'menu' => 'home',
			'domaines' => $this->utility->domaine()
		]);
	}
}
