<?php

namespace App\Notification\Template;

use App\Entity\User;

interface NotificationInterface
{
    public function getSmsText(): ?string;

    public function getParametersForEmail(): ?array;

    public function getPathToEmailTemplate(): ?string;

    public function getEmailTitle(): ?string;

    public function isToSms(): bool;

    public function isToEmail(): bool;

    public function getUser(): User;
}
