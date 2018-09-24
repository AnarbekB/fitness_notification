<?php

namespace App\Constants;

use MyCLabs\Enum\Enum;

/**
 * @method static LessonType MAN()
 * @method static LessonType WOMAN()
 */
//todo maybe move this entity to database
class LessonType extends Enum
{
    const STADIUM = 'stadium';
    const HALL = 'hall';
    const PARK = 'park';
}
