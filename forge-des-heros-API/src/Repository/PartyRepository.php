<?php

namespace App\Repository;

use App\Entity\Party;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Party>
 */
class PartyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Party::class);
    }

    /**
     * @return Party[]
     */
    public function findByCapacityStatus(string $status): array
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.id', 'ASC');

        if ('full' === $status) {
            $qb
                ->andWhere('p.maxSize IS NOT NULL')
                ->andWhere('SIZE(p.characters) >= p.maxSize');
        }

        if ('available' === $status) {
            $qb->andWhere('p.maxSize IS NULL OR SIZE(p.characters) < p.maxSize');
        }

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Party[] Returns an array of Party objects
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

//    public function findOneBySomeField($value): ?Party
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
