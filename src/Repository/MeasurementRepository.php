<?php

namespace App\Repository;

use App\Entity\City;
use App\Entity\Measurement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Measurement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Measurement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Measurement[]    findAll()
 * @method Measurement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeasurementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Measurement::class);
    }

    public function findByLocation(City $city)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->where('m.city_id = :city_id')
            ->setParameter('city_id', $city->getId())
            ->andWhere('m.date <= :now')
            ->setParameter('now', date('Y-m-d'))
        ->orderBy('m.date','desc');

        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function findByLocationToday(City $city)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->where('m.city_id = :city_id')
            ->setParameter('city_id', $city->getId())
            ->andWhere('m.date = :now')
            ->setParameter('now', date('Y-m-d'));
        $query = $qb->getQuery();
        return $query->getResult();
    }

    // /**
    //  * @return Measurement[] Returns an array of Measurement objects
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
    public function findOneBySomeField($value): ?Measurement
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
