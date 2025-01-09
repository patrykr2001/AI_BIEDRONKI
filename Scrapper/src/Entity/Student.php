<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $number = null;

    /**
     * @var Collection<int, Group>
     */
    #[ORM\ManyToMany(targetEntity: Group::class)]
    private Collection $groupId;

    public function __construct()
    {
        $this->groupId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

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
