<?php

namespace App\Repository;

use App\Entity\Subject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subject>
 */
class SubjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subject::class);
    }

    public function clearCache(): void
    {
        $_em = $this->getEntityManager();
        $_em->clear();
    }

    /**
     * @return Subject[] Returns an array of Subject objects
     */
    public function findAllSubjects(): array
    {
        return $this->createQueryBuilder('s')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Subject|null Returns a Subject object or null
     */
    public function findSubjectById(int $id): ?Subject
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }


    /**
     * @return Subject|null Returns a Subject object or null
     */
    public function findSubjectByName(string $name): ?Subject
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findSubjectByNameGetID(string $name): ?string
    {
        return $this->createQueryBuilder('s')
            ->select('s.id')
            ->andWhere('s.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult()->getId();
    }

    /**
     * Saves a Subject entity
     */
    public function save(Subject $subject): void
    {
        $_em = $this->getEntityManager();

        $_em->persist($subject);
        $_em->flush();
    }

    /**
     * Saves a list of Subject entities
     */
    public function saveSubjects(array $subjects): void
    {
        $_em = $this->getEntityManager();

        foreach ($subjects as $subject) {
            $_em->persist($subject);
        }
        $_em->flush();
    }
}
