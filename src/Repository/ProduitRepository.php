<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }
       //recherche
       public function search($searchTerm)
       {
           return $this->createQueryBuilder('f')
               ->where('f.nom LIKE :searchTerm')
               
               ->orWhere('f.cout LIKE :searchTerm')
               ->orWhere('f.quantite LIKE :searchTerm')
               ->setParameter('searchTerm', '%' . $searchTerm . '%')
               ->getQuery()
               ->getResult();
       }
       // Ajouter ces méthodes à votre ProduitRepository

       public function trierParCoutDecroissant()
       {
           return $this->createQueryBuilder('p')
               ->orderBy('p.cout', 'DESC')
               ->getQuery()
               ->getResult();
       }
        



       

public function getQuantiteProduitsParFournisseur()
{
    return $this->createQueryBuilder('p')
        ->select('f.nom as nom, SUM(p.quantite) as quantite')
         //Effectue une jointure entre l'entité Produit ('p') et l'entité Fournisseur ('f') sur la clé étrangère idFournisseur
         //Cela permet d'associer chaque produit à son fournisseur correspondant
        ->leftJoin('p.idFournisseur', 'f')
        ->groupBy('f.nom')
        ->getQuery()
        ->getResult();
}


//    /**
//     * @return Produit[] Returns an array of Produit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
