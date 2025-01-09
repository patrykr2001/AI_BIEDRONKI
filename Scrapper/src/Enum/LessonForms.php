<?php

namespace App\Enum;

enum LessonForms: string
{
    case Lecture = 'wykład';
    case Laboratory = 'labolatorium';
    case Remote = 'zajęcia zdalne';
    case Seminar = 'seminarium';
    case Project = 'projekt';
    case Pass = 'zaliczenie';
    case RemotePass = 'zaliczenie zdalne';
}