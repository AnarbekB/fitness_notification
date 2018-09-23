<?php

namespace App\Object;

use App\Entity\User;

class SmsObject
{
    /** @var  string */
    public $text;

    /** @var  User */
    public $user;

    /** @var  string */
    public $phone;

    /**
     * @param User $user
     * @param string $text
     * @return SmsObject
     */
    public static function fromUser(User $user, string $text)
    {
        $object = new self();

        $object->text = $text;
        $object->phone = $user->getPhone();
        $object->user = $user;

        return $object;
    }

    /**
     * @param string $phone
     * @param string $text
     * @return SmsObject
     */
    public static function fromPhone(string $phone, string $text)
    {
        $object = new self();

        $object->text = $text;
        $object->phone = validate_phone($phone);

        return $object;
    }
}
