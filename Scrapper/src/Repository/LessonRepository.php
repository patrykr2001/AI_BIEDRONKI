<?php

namespace App\Repository;

use App\Entity\Lesson;
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
        $this->_em->persist($lesson);
        $this->_em->flush();
    }

    /**
     * Finds a Lesson by its name.
     *
     * @param string $name
     * @return Lesson|null
     */
    public function findLessonByName(string $name): ?Lesson
    {
        return $this->findOneBy(['name' => $name]);
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
}