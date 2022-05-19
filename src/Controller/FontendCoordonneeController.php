<?php
	
	namespace App\Controller;
	
	use App\Repository\CoordonneeRepository;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Routing\Annotation\Route;
	
	#[Route('/coordonnee')]
	class FontendCoordonneeController extends AbstractController
	{
		#[Route('/', name:'frontend_coordonnee')]
		public function index(CoordonneeRepository $coordonneeRepository)
		{
			return $this->render('home/coordonnees.html.twig',[
				'coordonnee' => $coordonneeRepository->findOneBy([],['id'=>'DESC'])
			]);
		}
	}
