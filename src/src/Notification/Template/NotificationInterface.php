<?php

namespace App\Notification\Template;

use App\Entity\User;

interface NotificationInterface
{
    public function getSmsText(): ?string;

    public function setSmsText();

    public function getParametersForEmail(): ?array;

    public function setParametersForEmail();

    public function getPathToEmailTemplate(): ?string;

    public function getEmailTitle(): ?string;

    public function isToSms(): bool;

    public function isToEmail(): bool;

    public function getUser(): User;
}
