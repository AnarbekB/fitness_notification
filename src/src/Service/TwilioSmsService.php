<?php

namespace App\Service;

use Twilio\Rest\Client;

class TwilioSmsService implements SmsGateServiceInterface
{
    private $twilioGate;

    public function __construct(Client $twilioGate)
    {
        $this->twilioGate = $twilioGate;
    }

    /**
     * @param string $receiverPhoneNumber
     * @param string $message
     * @param null|string $sender
     */
    public function send(string $receiverPhoneNumber, string $message, ?string $sender = null): void
    {
        $this->twilioGate->messages->create(
            $receiverPhoneNumber,
            [
                'from' => $sender ? $sender : getenv('TWILIO_FROM'),
                'body' => $message
            ]
        );
    }
}
