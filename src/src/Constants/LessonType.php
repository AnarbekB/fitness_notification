<?php

namespace App\Constants;

use MyCLabs\Enum\Enum;

/**
 * @method static LessonType STADIUM()
 * @method static LessonType HALL()
 * @method static LessonType PARK()
 */
//todo maybe move this entity to database
class LessonType extends Enum
{
    const STADIUM = 'Стадион';
    const HALL = 'Зал';
    const PARK = 'Парк';
}
