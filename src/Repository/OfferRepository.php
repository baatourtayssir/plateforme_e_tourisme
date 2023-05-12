<?php

namespace App\Repository;

use App\Entity\Offer;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;


/**
 * @extends ServiceEntityRepository<Offer>
 *
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    public function save(Offer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Offer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOffresByPays($pays)
{
    return $this->createQueryBuilder('o')
        ->where('o.pays = :pays')
        ->setParameter('pays', $pays)
        ->orderBy('o.id', 'ASC')
        ->getQuery()
        ->getResult();
}


public function findOffresByCountries($country)
{
    $qb = $this->createQueryBuilder('o');
    
    $qb->andWhere(':country MEMBER OF o.countries')
       ->setParameter('country', $country);
       
    return $qb->getQuery()->getResult();
}



public function search(SearchData $search): array
{
    $query = $this->createQueryBuilder('o')
        ->leftJoin('o.countries', 'c')
        ->andWhere('o.title LIKE :q OR o.description LIKE :q')
        ->setParameter('q', '%'.$search->q.'%');

    if (!empty($search->country)) {
        $query->andWhere('c.intitule = :country')
            ->setParameter('country', $search->country);
    }

    return $query->getQuery()->getResult();
}



//    /**
//     * @return Offer[] Returns an array of Offer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Offer
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
