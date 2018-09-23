<?php

namespace App\Service;

interface SmsGateServiceInterface
{
    /**
     * @param string $receiverPhoneNumber Phone number
     * @param string $message             Message content
     * @param string|null $sender              Sender name
     */
    public function send(string $receiverPhoneNumber, string $message, ?string $sender = null): void;
}
