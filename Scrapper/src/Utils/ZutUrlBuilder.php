<?php

declare(strict_types=1);

namespace App\Utils;

use App\Enum\ZutDataKinds;
use App\Enum\ZutEndpoints;
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

    public function buildScheduleUrl(array $data, DateTime $start, DateTime $end): string
    {
        $url = sprintf('%s/%s?', $this->baseUrl, ZutEndpoints::Schedule->value);

        foreach($data as $kind => $query){
            $url .= sprintf('%s=%s&', $kind, urlencode($query));
        }

        $url .= sprintf('start=%s&end=%s',
            urlencode($start->format(DateTimeInterface::ATOM)),
            urlencode($end->format(DateTimeInterface::ATOM)));

        return $url;
    }
}