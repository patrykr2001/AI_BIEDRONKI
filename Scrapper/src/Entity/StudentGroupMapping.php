<?php

namespace App\Entity;

use App\Repository\StudentGroupMappingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentGroupMappingRepository::class)]
class StudentGroupMapping
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Student>
     */
    #[ORM\ManyToMany(targetEntity: Student::class)]
    private Collection $studentId;

    /**
     * @var Collection<int, Group>
     */
    #[ORM\ManyToMany(targetEntity: Group::class)]
    private Collection $groupId;

    public function __construct()
    {
        $this->studentId = new ArrayCollection();
        $this->groupId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudentId(): Collection
    {
        return $this->studentId;
    }

    public function addStudentId(Student $studentId): static
    {
        if (!$this->studentId->contains($studentId)) {
            $this->studentId->add($studentId);
        }

        return $this;
    }

    public function removeStudentId(Student $studentId): static
    {
        $this->studentId->removeElement($studentId);

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroupId(): Collection
    {
        return $this->groupId;
    }

    public function addGroupId(Group $groupId): static
    {
        if (!$this->groupId->contains($groupId)) {
            $this->groupId->add($groupId);
        }

        return $this;
    }

    public function removeGroupId(Group $groupId): static
    {
        $this->groupId->removeElement($groupId);

        return $this;
    }
}
