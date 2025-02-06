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

    /**
     * Trouve les véhicules en fonction des filtres.
     *
     * @param array $filters Les critères de recherche (marque, prixMax, disponible)
     * @return Vehicule[]
     */
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
}