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
            ->leftJoin('c.race', 'r');

        if ($name) {
            $qb->andWhere('c.name LIKE :name')
            ->setParameter('name', '%' . $name . '%');
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



}
