<?php

namespace App\Repository;

use App\Entity\Competition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Competition>
 *
 * @method Competition|null find($id, $lockMode = null, $lockVersion = null)
 * @method Competition|null findOneBy(array $criteria, array $orderBy = null)
 * @method Competition[]    findAll()
 * @method Competition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Competition::class);
    }
    public function findBySearchCriteria($name, $ongoing)
    {
        $qb = $this->createQueryBuilder('c');

    if ($name) {
        $qb->andWhere('c.nom LIKE :name')
           ->setParameter('name', '%'.$name.'%');
    }

    if ($ongoing) {
        $qb->andWhere('c.date > :today')
           ->setParameter('today', new \DateTime()); // Let Doctrine infer the type
    }
    return $qb->getQuery()->getResult();
    }

    public function getAverageReservationsPerDayOfWeek()
    {
        $entityManager = $this->getEntityManager(); // Get the EntityManager

        $sql = 'SELECT DAYOFWEEK(c.date) AS dayOfWeek, COUNT(r.id) / COUNT(DISTINCT c.id) AS avgReservationsPerCompetition
                FROM competition c
                JOIN reservation r ON c.id = r.fk_competition_id
                GROUP BY DAYOFWEEK(c.date)';

        $connection = $entityManager->getConnection();
        $stmt = $connection->prepare($sql);

        // Use executeQuery for SELECT statements
        $result = $stmt->executeQuery(); // Updated to use executeQuery

        return $result->fetchAllAssociative(); 
    }
    public function getAverageReservationsPerMonth()
    {
        $entityManager = $this->getEntityManager();

        $sql = 'SELECT MONTH(c.date) AS month, COUNT(r.id) / COUNT(DISTINCT c.id) AS avgReservationsPerCompetition
                FROM competition c
                JOIN reservation r ON c.id = r.fk_competition_id
                GROUP BY MONTH(c.date)';

        $connection = $entityManager->getConnection();
        $stmt = $connection->prepare($sql);

        // Use executeQuery for SELECT statements
        $result = $stmt->executeQuery(); // Updated to use executeQuery

        return $result->fetchAllAssociative(); 
    }
    public function getMonthlyReservationsByOrganizer()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT o.nom AS organizerName, 
                MONTH(c.date) AS month, 
                COALESCE(AVG(r.count), 0) AS avgReservations 
            FROM organisateur o 
            JOIN competition c ON o.id = c.fk_organisateur_id 
            LEFT JOIN (SELECT fk_competition_id, COUNT(*) AS count FROM reservation GROUP BY fk_competition_id) r ON c.id = r.fk_competition_id 
            GROUP BY o.nom, MONTH(c.date) 
            ORDER BY o.nom, MONTH(c.date)
        ";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchAllAssociative();
    }
//    /**
//     * @return Competition[] Returns an array of Competition objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Competition
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
