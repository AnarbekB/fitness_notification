<?php

namespace App\Notification\Template;

use App\Entity\User;

class RegistrationSuccess extends Notification
{
    protected $toEmail = true;

    protected $toSms = false;

    protected $pathToEmailTemplate = 'emails/reset_password.html.twig';

    protected $emailTitle = 'Подтверждение регистрации';

    /** @var User $user */
    protected $user;

    /** @var string $linkSetPassword */
    protected $linkSetPassword;

    public function __construct(User $user, string $linkSetPassword)
    {
        $this->user = $user;
        $this->linkSetPassword = $linkSetPassword;
    }

    public function getEmailTitle(): ?string
    {
        return $this->emailTitle;
    }

    public function getPathToEmailTemplate(): ?string
    {
        return 'emails/reset_password.html.twig';
    }

    public function getParametersForEmail(): ?array
    {
        return [
            'fullName' => $this->user->getFullName(),
            'user' => $this->user
        ];
    }

    public function getSmsText(): ?string
    {
        return null;
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
}
