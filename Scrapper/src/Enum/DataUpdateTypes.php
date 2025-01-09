<?php

namespace App\Enum;

enum DataUpdateTypes: string
{
    case Monthly = 'monthly';
    case Weekly = 'weekly';
    case Daily = 'daily';
}