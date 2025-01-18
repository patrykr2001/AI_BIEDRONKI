<?php

namespace App\Repository;

use App\Entity\Teacher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Teacher>
 */
class TeacherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Teacher::class);
    }

    public function clearCache(): void{
        $_em = $this->getEntityManager();
        $_em->clear();
    }

    /**
     * @return Teacher[] Returns an array of Teacher objects
     */
    public function findAllTeachers(): array
    {
        return $this->createQueryBuilder('t')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Teacher|null Returns a Teacher object or null
     */
    public function findTeacherById(int $id): ?Teacher
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Teacher|null Returns a Teacher object or null
     */
    public function findTeacherByName(string $name): ?Teacher
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Saves a Teacher entity
     */
    public function saveTeacher(Teacher $teacher): void
    {
        $_em = $this->getEntityManager();

        $_em->persist($teacher);
        $_em->flush();
    }

    /**
     * Saves a list of Teacher entities
     */
    public function saveTeachers(array $teachers): void
    {
        $_em = $this->getEntityManager();

        foreach ($teachers as $teacher) {
            $_em->persist($teacher);
        }
        $_em->flush();
    }
}
