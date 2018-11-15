<?php

namespace App\Constants;

use MyCLabs\Enum\Enum;

/**
 * @method static ChannelNotification PHONE()
 * @method static ChannelNotification EMAIL()
 * @method static ChannelNotification NOTHING()
 */

class ChannelNotification extends Enum
{
    const PHONE = 'phone';
    const EMAIL = 'email';
    const NOTHING = 'nothing';
}
