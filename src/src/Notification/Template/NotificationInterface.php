<?php

namespace App\Notification\Template;

use App\Entity\User;

interface NotificationInterface
{
    //methods for sms
    public function getSmsText(): ?string;

    public function setSmsText(array $fields = null);

    public function isToSms(): bool;

    //methods for email
    public function getParametersForEmail(): ?array;

    public function setParametersForEmail(array $fields = null);

    public function getPathToEmailTemplate(): ?string;

    public function getEmailTitle(): ?string;

    public function getEmailCustomMessage(): ?string;

    public function setEmailCustomMessage(array $fields = null);

    public function isToEmail(): bool;

    public function getUser(): User;
}
