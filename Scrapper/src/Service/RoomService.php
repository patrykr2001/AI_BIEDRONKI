<?php

namespace App\Service;

use App\Entity\Room;
use App\Repository\RoomRepository;

class RoomService
{
    private RoomRepository $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function getAllRooms(): array
    {
        return $this->roomRepository->findAllRooms();
    }

    public function getRoomById(int $id): ?Room
    {
        return $this->roomRepository->findRoomById($id);
    }

    public function getRoomByName(string $name): ?Room
    {
        return $this->roomRepository->findRoomByName($name);
    }

    public function saveRoom(Room $room): void
    {
        $this->roomRepository->saveRoom($room);
    }

    public function saveRooms(array $rooms): void
    {
        $this->roomRepository->saveRooms($rooms);
    }

    public function saveNewRooms(array $rooms): void
    {
        $newRooms = [];
        foreach ($rooms as $room) {
            if ($this->roomRepository->findRoomByName($room->getName()) === null) {
                $newRooms[] = $room;
            }
        }
        if (!empty($newRooms)) {
            $this->roomRepository->saveRooms($newRooms);
        }
    }
}