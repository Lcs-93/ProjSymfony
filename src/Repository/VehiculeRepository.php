<?php

namespace App\Repository;

use App\Entity\Vehicule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vehicule>
 */
class VehiculeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicule::class);
    }
    public function findByFilters(array $filters): array
{
    $qb = $this->createQueryBuilder('v');

    if (!empty($filters['marque'])) {
        $qb->andWhere('v.marque LIKE :marque')
           ->setParameter('marque', '%' . $filters['marque'] . '%');
    }

    if (!empty($filters['prixMax'])) {
        $qb->andWhere('v.prixJournalier <= :prixMax')
           ->setParameter('prixMax', $filters['prixMax']);
    }

    if (!empty($filters['disponible'])) {
        $qb->andWhere('v.disponible = :disponible')
           ->setParameter('disponible', true);
    }

    return $qb->getQuery()->getResult();
}
    //    /**
    //     * @return Vehicule[] Returns an array of Vehicule objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Vehicule
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
