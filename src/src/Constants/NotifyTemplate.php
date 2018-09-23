<?php

namespace App\Constants;

use MyCLabs\Enum\Enum;

/**
 * Class SmsTemplate
 * @package App\Constants
 *
 * @method static NotifyTemplate CREATE_USER()
 */
class NotifyTemplate extends Enum
{
    const CREATE_USER = 'Здравствуйте, {fullName}, Ваш аккаунт создан. Установить пароль: {linkSetPassword}';
}
