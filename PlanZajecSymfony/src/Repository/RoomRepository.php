<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Room>
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function clearCache(): void{
        $_em = $this->getEntityManager();
        $_em->clear();
    }

    /**
     * @return Room[] Returns an array of Room objects
     */
    public function findAllRooms(): array
    {
        return $this->createQueryBuilder('r')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Room|null Returns a Room object or null
     */
    public function findRoomById(int $id): ?Room
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Room|null Returns a Room object or null
     */
    public function findRoomByName(string $name): ?Room
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findRoomByNameGetId(string $name): ?Room
    {
        return $this->createQueryBuilder('r')
            ->select('r.id')
            ->andWhere('r.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Saves a Room entity
     */
    public function saveRoom(Room $room): void
    {
        $_em = $this->getEntityManager();

        $_em->persist($room);
        $_em->flush();
    }

    /**
     * Saves a list of Room entities
     */
    public function saveRooms(array $rooms): void
    {
        $_em = $this->getEntityManager();

        foreach ($rooms as $room) {
            $_em->persist($room);
        }
        $_em->flush();
    }
}
