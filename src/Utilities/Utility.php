<?php

namespace App\Utilities;

use App\Entity\Adulte;
use App\Entity\Domaine;
use App\Entity\MenuAdulte;
use App\Entity\Newsletter;
use App\Repository\SoutienRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;

class Utility
{
    private $entityManager;
    private $soutienRepository;

    public function __construct(EntityManagerInterface $entityManager, SoutienRepository $soutienRepository)
    {
        $this->entityManager = $entityManager;
        $this->soutienRepository= $soutienRepository;
    }
	
	/**
	 * @return array
	 */
    public function soutien(): array
    {
        $soutiens = $this->soutienRepository->findBy([],['affichage'=>"ASC"]); 

        return $soutiens;
    }
	
	/**
	 * @return array
	 */
	public function domaine(): array
	{
		return $this->entityManager->getRepository(Domaine::class)->findBy(['statut'=>true]);
	}
	
	/**
	 * Vérification de l'adresse email
	 *
	 * @param $newsletter
	 * @return Newsletter|mixed|object|null
	 */
	public function verifEmail($newsletter): mixed
	{
		return $this->entityManager->getRepository(Newsletter::class)->findOneBy(['email'=>$newsletter->getEmail()]);
	}

    /**
     * Formattage du contenu du texte avec éléminitation des balises HTML pour le resumé du texte
     *
     * @param $string
     * @return false|string
     */
    public function resume($string)
    {
        return substr(strip_tags($string), 0, 155);
    }
	
	/**
	 * Formattage du slug
	 *
	 * @param $str
	 * @return string
	 */
	public function slug($str): string
	{
		$slugify = new Slugify();
		return $slugify->slugify($str);
	}
	
	public function menuAdulte(): array
	{
		$menus = $this->entityManager->getRepository(MenuAdulte::class)->findAll();
		
		$lists=[];$adultes=[];$i=0;$j=0;
		foreach ($menus as $menu){
			$sous_menus = $this->entityManager->getRepository(Adulte::class)->findBy(['menu' => $menu->getId()]);
			foreach ($sous_menus as $sous_menu){
				$lists[$j++] = [
					'menu_adulte' => $sous_menu->getTitre(),
					'menu_slug' => $sous_menu->getSlug(),
				];
			}
			$adultes[$i++] = [
				'menu' => $menu->getTitre(),
				'lists' => $lists,
				'slug' => $menu->getSlug()
			];
			$lists = [];
		}
		
		return $adultes;
	}

}
