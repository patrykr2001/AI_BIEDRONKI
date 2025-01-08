<?php

namespace App\Enum;
enum ZutScheduleDataKinds: string {
    case Teachers = 'teacher';
    case Rooms = 'room';
    case Subjects = 'subject';
    case Groups = 'group';
    case Student = 'number';
}
