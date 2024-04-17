<?php

namespace App\Entity;

use App\Entity\Usersmeal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Usersmeal>
 *
 * @method Usersmeal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Usersmeal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Usersmeal[]    findAll()
 * @method Usersmeal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersmealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Usersmeal::class);
    }

//    /**
//     * @return Usersmeal[] Returns an array of Usersmeal objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Usersmeal
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
