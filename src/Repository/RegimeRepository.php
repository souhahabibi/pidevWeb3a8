<?php

namespace App\Repository;

use App\Entity\Regime;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Regime>
 *
 * @method Regime|null find($id, $lockMode = null, $lockVersion = null)
 * @method Regime|null findOneBy(array $criteria, array $orderBy = null)
 * @method Regime[]    findAll()
 * @method Regime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Regime::class);
    }

//    /**
//     * @return Regime[] Returns an array of Regime objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

/**
     * Find regimes by a specific client.
     *
     * @param User $client
     * @return Regime[] Returns an array of Regime objects
     */
    public function findByClient(User $client): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.clientId = :client')
            ->setParameter('client', $client)
            ->getQuery()
            ->getResult();
    }

//    public function findOneBySomeField($value): ?Regime
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
