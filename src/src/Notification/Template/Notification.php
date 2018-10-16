<?php

namespace App\Notification\Template;

use App\Entity\User;

abstract class Notification implements NotificationInterface
{
    /** @var  bool */
    protected $toSms;

    /** @var  bool */
    protected $toEmail;

    /** @var  string | null */
    protected $smsText;

    /** @var  string | null */
    protected $pathToEmailTemplate;

    /** @var  string | null */
    protected $emailTitle;

    /** @var  User */
    protected $user;

    abstract public function getEmailTitle(): ?string;

    abstract public function getParametersForEmail(): ?array;

    abstract public function getSmsText(): ?string;

    abstract public function getPathToEmailTemplate(): ?string;

    abstract public function isToSms(): bool;

    abstract public function isToEmail(): bool;

    abstract public function getUser(): User;
}
