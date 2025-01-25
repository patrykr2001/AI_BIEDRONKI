<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Student>
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    public function clearCache(): void
    {
        $_em = $this->getEntityManager();
        $_em->clear();
    }

    /**
     * @return Student[]
     */
    public function findAllStudents(): array
    {
        return $this->findAll();
    }

    /**
     * @param string $number
     * @return Student|null
     */
    public function findStudentByNumber(string $number): ?Student
    {
        return $this->findOneBy(['number' => $number]);
    }


    /**
     * @param Student $student
     * @return void
     */
    public function saveStudent(Student $student): void
    {
        $_em = $this->getEntityManager();

        $_em->persist($student);
        $_em->flush();
    }

    public function updateStudentGroupsAll(array $groups, string $number): void
    {
        $_em = $this->getEntityManager();

        $student = $this->findStudentByNumber($number);
        $dbGroups = $student->getGroupId();

        foreach ($dbGroups as $group) {
            $student->removeGroupId($group);
        }

        foreach ($groups as $group) {
            $student->addGroupId($group);
        }

        $_em->flush();
    }

    public function updateStudentGroups(array $groups, string $number): void
    {
        $_em = $this->getEntityManager();

        $student = $this->findStudentByNumber($number);
        $dbGroups = $student->getGroupId();

        foreach ($groups as $group) {
            if (!$dbGroups->contains($group)) {
                $student->addGroupId($group);
            }
        }

        $_em->flush();
    }
}
