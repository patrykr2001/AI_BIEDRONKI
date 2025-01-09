<?php

namespace App\Service;

use App\Entity\DataUpdateLog;
use App\Enum\DataUpdateTypes;
use App\Repository\DataUpdateLogRepository;

class DataUpdateLogService
{
    private DataUpdateLogRepository $dataUpdateLogRepository;

    public function __construct(DataUpdateLogRepository $dataUpdateLogRepository)
    {
        $this->dataUpdateLogRepository = $dataUpdateLogRepository;
    }

    /**
     * Finds the last DataUpdateLog record for the given type.
     *
     * @param DataUpdateTypes $type
     * @return DataUpdateLog|null
     */
    public function findLastByType(DataUpdateTypes $type): ?DataUpdateLog
    {
        return $this->dataUpdateLogRepository->findLastByType($type);
    }

    /**
     * Saves a new DataUpdateLog record.
     *
     * @param DataUpdateLog $dataUpdateLog
     */
    public function save(DataUpdateLog $dataUpdateLog): void
    {
        $this->dataUpdateLogRepository->save($dataUpdateLog);
    }
}