<?php

namespace App\Manager;

use App\Object\SmsObject;

interface SmsGateManagerInterface
{
    public function send(SmsObject $message): void;
}
