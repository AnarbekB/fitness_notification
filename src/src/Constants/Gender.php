<?php

namespace App\Constants;

use MyCLabs\Enum\Enum;

/**
 * @method static Gender MAN()
 * @method static Gender WOMAN()
 */

class Gender extends Enum
{
    const MAN = 'Мужской';
    const WOMAN = 'Женский';
}
