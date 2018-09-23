<?php

namespace App\Manager;

use App\Object\SmsObject;
use App\Service\SmsGateServiceInterface;

class SmsGateManager implements SmsGateManagerInterface
{
    protected $smsGate;

    public function __construct(SmsGateServiceInterface $smsGate)
    {
        $this->smsGate = $smsGate;
    }

    public function send(SmsObject $message): void
    {
        $phone = format_phone($message->phone);
        $this->smsGate->send($phone, $message->text);
    }
}
