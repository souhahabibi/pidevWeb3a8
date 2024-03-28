<?php

namespace App\Repository;

use App\Entity\IngredientMeal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IngredientMeal>
 *
 * @method IngredientMeal|null find($id, $lockMode = null, $lockVersion = null)
 * @method IngredientMeal|null findOneBy(array $criteria, array $orderBy = null)
 * @method IngredientMeal[]    findAll()
 * @method IngredientMeal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientMealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IngredientMeal::class);
    }

//    /**
//     * @return IngredientMeal[] Returns an array of IngredientMeal objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?IngredientMeal
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


public function findByMealId(int $mealId): array
{
    return $this->createQueryBuilder('im')
        ->join('im.meal', 'm')
        ->andWhere('m.id = :mealId')
        ->setParameter('mealId', $mealId)
        ->getQuery()
        ->getResult();
}

public function findByMealAndIngredient($mealId, $ingredientId): ?IngredientMeal
    {
        return $this->createQueryBuilder('im')
            ->andWhere('im.meal = :mealId')
            ->andWhere('im.ingredients = :ingredientId')
            ->setParameter('mealId', $mealId)
            ->setParameter('ingredientId', $ingredientId)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
}
