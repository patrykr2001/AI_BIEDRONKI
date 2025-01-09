<?php

namespace App\Repository;

use App\Entity\DataUpdateLog;
use App\Enum\DataUpdateTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DataUpdateLog>
 */
class DataUpdateLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataUpdateLog::class);
    }

    /**
     * @return DataUpdateLog|null Returns the last DataUpdateLog record for the given type or null
     */
    public function findLastByType(DataUpdateTypes $type): ?DataUpdateLog
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.type = :type')
            ->setParameter('type', $type)
            ->orderBy('d.updateDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Saves a new DataUpdateLog record.
     *
     * @param DataUpdateLog $dataUpdateLog
     */
    public function save(DataUpdateLog $dataUpdateLog): void
    {
        $_em = $this->getEntityManager();

        $_em->persist($dataUpdateLog);
        $_em->flush();
    }
}
