<?php

namespace App\Notification\Template;

use App\Entity\User;

class RegistrationSuccess extends Notification
{
    protected $toEmail = true;

    /** @var  array | null */
    protected $paramForEmail;

    protected $toSms = false;

    protected $pathToEmailTemplate = 'emails/reset_password.html.twig';

    protected $emailTitle = 'Подтверждение регистрации';

    /** @var User $user */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->setParametersForEmail();
    }

    public function getEmailTitle(): ?string
    {
        return $this->emailTitle;
    }

    public function getPathToEmailTemplate(): ?string
    {
        return $this->pathToEmailTemplate;
    }

    public function setParametersForEmail()
    {
        $this->paramForEmail = [
            'fullName' => $this->user->getFullName(),
            'projectUrl' => getenv('PROJECT_URL'),
            'passwordResetGuid' => $this->user->getPasswordResetGuid()
        ];
    }

    public function getParametersForEmail(): ?array
    {
        return $this->paramForEmail;
    }

    public function setSmsText()
    {
        $this->smsText = null;
    }

    public function getSmsText(): ?string
    {
        return $this->smsText;
    }

    /**
     * @return bool
     */
    public function isToEmail(): bool
    {
        return $this->toEmail;
    }

    /**
     * @return bool
     */
    public function isToSms(): bool
    {
        return $this->toSms;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
