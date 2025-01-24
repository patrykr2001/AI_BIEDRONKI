<?php

namespace App\Repository;

use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Group>
 */
class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    /**
     * @return Group[] Returns an array of Group objects
     */
    public function findAllGroups(): array
    {
        return $this->createQueryBuilder('r')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Group|null Returns a Group object or null
     */
    public function findGroupById(int $id): ?Group
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Group|null Returns a Group object or null
     */
    public function findGroupByName(string $name): ?Group
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findGroupByNameGetId(string $name): ?Group
    {
        return $this->createQueryBuilder('r')
            ->select('r.id')
            ->andWhere('r.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Saves a Group entity
     */
    public function save(Group $group): void
    {
        $_em = $this->getEntityManager();

        $_em->persist($group);
        $_em->flush();
    }

    /**
     * Saves a list of Group entities
     */
    public function saveGroups(array $groups): void
    {
        $_em = $this->getEntityManager();

        foreach ($groups as $group) {
            $_em->persist($group);
        }
        $_em->flush();
    }
}
