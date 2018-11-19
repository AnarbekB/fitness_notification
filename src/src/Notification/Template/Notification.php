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

    /** @var array | null */
    protected $paramForEmail;

    /** @var string | null */
    protected $emailCustomMessage;

    /** @var  User */
    protected $user;

    //methods for sms
    abstract public function getSmsText(): ?string;

    abstract public function setSmsText(array $fields = null);

    abstract public function isToSms(): bool;

    //methods for email
    abstract public function getEmailTitle(): ?string;

    abstract public function getParametersForEmail(): ?array;

    abstract public function setParametersForEmail(array $fields = null);

    abstract public function getPathToEmailTemplate(): ?string;

    abstract public function getEmailCustomMessage(): ?string;

    abstract public function setEmailCustomMessage(array $fields = null);

    abstract public function isToEmail(): bool;

    abstract public function getUser(): User;
}
