<?php

namespace App\Repository;

use App\Entity\MenuAdulte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MenuAdulte|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuAdulte|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuAdulte[]    findAll()
 * @method MenuAdulte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuAdulteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuAdulte::class);
    }

    public function liste()
    {
        return $this->createQueryBuilder('m')->orderBy('m.titre', 'ASC');
    }

    public function findByVar($var)
    {
        return $this->createQueryBuilder('m')
            ->where('m.titre LIKE :var' )
            ->setParameter('var', '%'.$var.'%')
            ->getQuery()->getOneOrNullResult()
            ;
    }
	
	public function findByMenu($slug)
	{
		return $this->createQueryBuilder('m')
			->where('m.titre = :slug')
			->setParameter('slug', $slug)
			->getQuery()->getResult()
			;
	}

    // /**
    //  * @return MenuAdulte[] Returns an array of MenuAdulte objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MenuAdulte
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
