<?php

namespace App\Repository;

use App\Entity\Character;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Character>
 */
class CharacterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Character::class);
    }

//    /**
//     * @return Character[] Returns an array of Character objects
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

//    public function findOneBySomeField($value): ?Character
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


    public function searchByName(string $name): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Character[]
     */
    public function findForApi(?string $name, ?int $classId, ?int $raceId): array
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.characterClass', 'cc')
            ->leftJoin('c.race', 'r')
            ->leftJoin('c.parties', 'p')
            ->addSelect('cc', 'r', 'p')
            ->orderBy('c.name', 'ASC');

        if (null !== $name && '' !== trim($name)) {
            $qb
                ->andWhere('c.name LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }

        if (null !== $classId) {
            $qb
                ->andWhere('cc.id = :classId')
                ->setParameter('classId', $classId);
        }

        if (null !== $raceId) {
            $qb
                ->andWhere('r.id = :raceId')
                ->setParameter('raceId', $raceId);
        }

        return $qb->getQuery()->getResult();
    }

    // Pour les filtre des perso par class et race
    public function findWithFilters(?string $search, ?string $classId, ?string $raceId): array
    {
        $qb = $this->createQueryBuilder('c') -> leftJoin('c.characterClass', 'cc') -> leftJoin('c.race', 'r');

        if ($search) {
            $qb->andWhere('c.name LIKE :search')
            ->setParameter('search', '%' . $search . '%');
        }

        if ($classId) {
            $qb->andWhere('cc.id = :classId')
            ->setParameter('classId', $classId);
        }

        if ($raceId) {
            $qb->andWhere('r.id = :raceId')
            ->setParameter('raceId', $raceId);
        }

        return $qb->orderBy('c.name', 'ASC')->getQuery()->getResult();
    }

    // public function searchByName(string $value): array
    // {
    //     return $this->createQueryBuilder('c')
    //         ->andWhere('c.name LIKE :val')
    //         ->setParameter('val', '%' . $value . '%')
    //         ->getQuery()
    //         ->getResult();
    // }

}
