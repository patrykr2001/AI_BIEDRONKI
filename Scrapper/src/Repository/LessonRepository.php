<?php

namespace App\Repository;

use App\Entity\Lesson;
use App\Entity\Teacher;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Lesson>
 */
class LessonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lesson::class);
    }

    /**
     * Saves a Lesson entity.
     *
     * @param Lesson $lesson
     */
    public function save(Lesson $lesson): void
    {
        $_em = $this->getEntityManager();

        $_em->persist($lesson);
        $_em->flush();
    }

    /**
     * Finds a Lesson by its name.
     *
     * @param string $name
     * @return Lesson|null
     */
    public function findLessonByTeacherStartEnd(Teacher $teacher, DateTime $start, DateTime $end): ?Lesson
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.workerId = :teacher')
            ->andWhere('l.startDate >= :start')
            ->andWhere('l.endDate <= :end')
            ->setParameter('teacher', $teacher)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Finds all Lesson entities.
     *
     * @return Lesson[]
     */
    public function findAllLessons(): array
    {
        return $this->findAll();
    }

    /**
     * Removes a Lesson entity.
     *
     * @param Lesson $lesson
     */
    public function remove(Lesson $lesson): void
    {
        $_em = $this->getEntityManager();

        $_em->remove($lesson);
        $_em->flush();
    }
}