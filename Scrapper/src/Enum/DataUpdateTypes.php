<?php

namespace App\Enum;

enum DataUpdateTypes: string
{
    case Montly = 'monthly';
    case Weekly = 'weekly';
    case Daily = 'daily';
}