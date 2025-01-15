<?php

namespace App\Utils;

use DateInterval;
use DateTime;

class DateHelper
{
    public static function getCurrentWeek(): array
    {
        $startOfWeek = self::getTodayStart();
        $startOfWeek->setISODate((int)$startOfWeek->format('o'), (int)$startOfWeek->format('W'), 1);
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->add(new DateInterval('P6D'));

        return [$startOfWeek, $endOfWeek];
    }

    public static function getNextWeek(): array
    {
        $startOfNextWeek = self::getTodayStart();
        $startOfNextWeek->setISODate((int)$startOfNextWeek->format('o'), (int)$startOfNextWeek->format('W') + 1, 1);
        $endOfNextWeek = clone $startOfNextWeek;
        $endOfNextWeek->add(new DateInterval('P6D'));

        return [$startOfNextWeek, $endOfNextWeek];
    }

    public static function getPreviousWeek(): array
    {
        $startOfPreviousWeek = self::getTodayStart();
        $startOfPreviousWeek->setISODate((int)$startOfPreviousWeek->format('o'), (int)$startOfPreviousWeek->format('W') - 1, 1);
        $endOfPreviousWeek = clone $startOfPreviousWeek;
        $endOfPreviousWeek->add(new DateInterval('P6D'));

        return [$startOfPreviousWeek, $endOfPreviousWeek];
    }

    public static function getCurrentDay(): DateTime
    {
        return new DateTime();
    }

    public static function getTodayWithHourOneHourAgo(): DateTime
    {
        $date = new DateTime();
        $date->modify('-1 hour');
        return $date->setTime($date->format('H'), 0);
    }

    public static function getTodayWithHour(): DateTime
    {
        $date = new DateTime();
        return $date->setTime($date->format('H'), 0);
    }

    public static function getCurrentDayWithSpecificHour(int $hour, int $minute = 0): DateTime
    {
        return (new DateTime())->setTime($hour, $minute);
    }

    public static function getTodayStart(): DateTime
    {
        return (new DateTime())->setTime(0, 0);
    }

    public static function getTodayEnd(): DateTime
    {
        return (new DateTime())->setTime(23, 59);
    }

    public static function getTommorowStart(): DateTime
    {
        return (new DateTime())->modify('+1 day')->setTime(0, 0);
    }

    public static function getCurrentMonth(): array
    {
        $startOfMonth = new DateTime('first day of this month');
        $endOfMonth = new DateTime('last day of this month');

        return [$startOfMonth, $endOfMonth];
    }

    public static function getDate31DaysAgo(): DateTime
    {
        $date = self::getTodayStart();
        $date->sub(new DateInterval('P31D'));
        return $date;
    }

    public static function getDate8DaysAgo(): DateTime
    {
        $date = self::getTodayStart();
        $date->sub(new DateInterval('P8D'));
        return $date;
    }

    public static function getDateYesterday(): DateTime
    {
        $date = self::getTodayStart();
        $date->sub(new DateInterval('P1D'));
        return $date;
    }
}