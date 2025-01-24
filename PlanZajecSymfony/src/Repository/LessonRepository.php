<?php

namespace App\Repository;

use App\Entity\Group;
use App\Entity\Lesson;
use App\Entity\Subject;
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

    public function clearCache(): void{
        $_em = $this->getEntityManager();
        $_em->clear();
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
     * Finds a Lesson entity by its teacher ID, start date and end date.
     * @param Teacher $teacher
     * @param DateTime $start
     * @param DateTime $end
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
     * Finds a Lesson entity by its teacher ID, subject ID, group ID, start date and end date.
     * @param Teacher $teacher
     * @param Subject $subject
     * @param Group|null $group
     * @param DateTime $start
     * @param DateTime $end
     * @return Lesson|null
     */
    public function findLessonByTeacherSubjectGroupStartEnd(Teacher  $teacher, Subject $subject, ?Group $group,
                                                            DateTime $start, DateTime $end): ?Lesson
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.workerId = :teacher')
            ->andWhere('l.subjectId = :subject')
            ->andWhere('l.groupId = :group')
            ->andWhere('l.startDate >= :start')
            ->andWhere('l.endDate <= :end')
            ->setParameter('teacher', $teacher)
            ->setParameter('subject', $subject)
            ->setParameter('group', $group)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Finds a Lesson entity by its teacher ID, subject ID, group ID, start date and end date.
     * @param Teacher $teacher
     * @param Subject $subject
     * @param Group $group
     * @param DateTime $start
     * @param DateTime $end
     * @return Lesson|null
     */
    public function findLessonAPI(Teacher  $teacher, Subject $subject, Group $group, DateTime $start, DateTime $end): ?Lesson
    {
        $q =  $this -> createQueryBuilder('l');

        if($teacher!="")
            {
                $q  ->andWhere('l.workerId = :teacher');
            }
        if($subject!="")
            {
                $q  ->andWhere('l.subjectId = :subject');
            }
        if($group!="")
            {
                $q  ->andWhere('l.groupId IN :group');
            }


        $q  ->andWhere('l.startDate >= :start');
        $q  ->andWhere('l.endDate <= :end');

           $q->setParameter('teacher', $teacher)
            ->setParameter('subject', $subject)
            ->setParameter('group', $group)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getOneOrNullResult();
        return $q;
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