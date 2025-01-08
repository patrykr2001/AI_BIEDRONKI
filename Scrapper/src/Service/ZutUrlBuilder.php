<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\ZutEndpoints;
use App\Enum\ZutDataKinds;
use DateTime;
use DateTimeInterface;

class ZutUrlBuilder
{
    private string $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function buildDataUrl(ZutDataKinds $dataKind, string $query): string
    {
        return sprintf('%s/%s?kind=%s&query=%s',
            $this->baseUrl, ZutEndpoints::Data->value, $dataKind->value, urlencode($query));
    }

    public function buildScheduleUrl(ZutDataKinds $dataKind, string $query, DateTime $start, DateTime $end): string
    {
        return sprintf('%s/%s?kind=%s&query=%s&start=%s&end=%s',
            $this->baseUrl, ZutEndpoints::Schedule->value, $dataKind->value, urlencode($query),
            urlencode($start->format(DateTimeInterface::ATOM)), urlencode($end->format(DateTimeInterface::ATOM)));
    }
}