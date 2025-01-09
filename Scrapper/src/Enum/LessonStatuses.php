<?php

namespace App\Enum;

enum LessonStatuses: string
{
    case Normal = 'normalne';
    case Cancelled = 'odwołane';
}