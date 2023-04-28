<?php

namespace App\Repository;

use App\Entity\GoodAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GoodAddress>
 *
 * @method GoodAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method GoodAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method GoodAddress[]    findAll()
 * @method GoodAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GoodAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GoodAddress::class);
    }

    public function save(GoodAddress $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(GoodAddress $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return GoodAddress[] Returns an array of GoodAddress objects
     */
    /*    public function findByExampleField($value): array
   {
       return $this->createQueryBuilder('g')
           ->andWhere('g.exampleField = :val')
           ->setParameter('val', $value)
           ->orderBy('g.id', 'ASC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   } */

    /**
     * Finds GoodAddress entities by regions.
     *
     * @param array $regions An array of region names
     *
     * @return GoodAddress[] An array of GoodAddress entities that have one of the specified regions
     */
    public function findByRegions(array $regions): array
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.region', 'r')
            ->andWhere('r.name IN (:regions)')
            ->setParameter('regions', $regions)
            ->orderBy('g.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
    //    public function findOneBySomeField($value): ?GoodAddress
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
