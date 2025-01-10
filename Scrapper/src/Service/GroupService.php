<?php

namespace App\Service;

use App\Entity\Group;
use App\Repository\GroupRepository;

class GroupService
{
    private GroupRepository $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    /**
     * Saves a new Group record.
     *
     * @param Group $group
     */
    public function save(Group $group): void
    {
        $this->groupRepository->save($group);
    }

    /**
     * Saves an array of Group records.
     *
     * @param Group[] $groups
     */
    public function saveAll(array $groups): void
    {
        foreach ($groups as $group) {
            $this->groupRepository->save($group);
        }
    }

    /**
     * Saves new Group records if they do not already exist.
     *
     * @param Group[] $groups
     */
    public function saveNewGroups(array $groups): void
    {
        foreach ($groups as $group)
            if ($this->groupRepository->findByName($group->getName()) === null) {
                $this->groupRepository->save($group);
            }
    }

    /**
     * Retrieves a Group by its ID.
     *
     * @param int $id
     * @return Group|null
     */
    public function getGroupById(int $id): ?Group
    {
        return $this->groupRepository->findGroupById($id);
    }

    /**
     * Retrieves a Group by its name.
     *
     * @param string $name
     * @return Group|null
     */
    public function getGroupByName(string $name): ?Group
    {
        return $this->groupRepository->findGroupByName($name);
    }

    /**
     * Retrieves all Group records.
     *
     * @return Group[]
     */
    public function getAllGroups(): array
    {
        return $this->groupRepository->findAllGroups();
    }
}