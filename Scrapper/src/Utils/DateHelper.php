<?php

namespace App\Utils;

use DateInterval;
use DateTime;

class DateHelper
{
    public static function getCurrentWeek(): array
    {
        $startOfWeek = new DateTime();
        $startOfWeek->setISODate((int)$startOfWeek->format('o'), (int)$startOfWeek->format('W'), 1);
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->add(new DateInterval('P6D'));

        return [$startOfWeek, $endOfWeek];
    }

    public static function getNextWeek(): array
    {
        $startOfNextWeek = new DateTime();
        $startOfNextWeek->setISODate((int)$startOfNextWeek->format('o'), (int)$startOfNextWeek->format('W') + 1, 1);
        $endOfNextWeek = clone $startOfNextWeek;
        $endOfNextWeek->add(new DateInterval('P6D'));

        return [$startOfNextWeek, $endOfNextWeek];
    }

    public static function getCurrentDay(): DateTime
    {
        return new DateTime();
    }

    public static function getCurrentMonth(): array
    {
        $startOfMonth = new DateTime('first day of this month');
        $endOfMonth = new DateTime('last day of this month');

        return [$startOfMonth, $endOfMonth];
    }
}