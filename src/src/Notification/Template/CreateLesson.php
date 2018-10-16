<?php

namespace App\Notification\Template;

use App\Entity\User;

class CreateLesson extends Notification
{
    protected $toEmail = true;

    protected $toSms = false;

    /** @var  string | null */
    protected $smsText = 'test';

    protected $pathToEmailTemplate = 'emails/create_lessons.html.twig';

    protected $emailTitle = 'Новое занятие';

    /** @var User $user */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getEmailTitle(): ?string
    {
        return $this->emailTitle;
    }

    public function getPathToEmailTemplate(): ?string
    {
        return $this->pathToEmailTemplate;
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

    public function getUser(): User
    {
        return $this->user;
    }
}
