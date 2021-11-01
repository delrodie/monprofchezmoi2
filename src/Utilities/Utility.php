<?php

namespace App\Utilities;

use App\Repository\SoutienRepository;
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

    public function soutien()
    {
        $soutiens = $this->soutienRepository->findBy([],['affichage'=>"ASC"]); 

        return $soutiens;
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



}
