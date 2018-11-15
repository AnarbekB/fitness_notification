<?php

namespace App\Notification\Template;

use App\Entity\GroupLesson;
use App\Entity\User;

class CreateLesson extends Notification
{
    protected $toEmail = true;

    protected $toSms = false;

    /** @var  string | null */
    protected $smsText = 'test';

    /** @var array | null */
    protected $paramForEmail;

    protected $pathToEmailTemplate = 'emails/create_lessons.html.twig';

    protected $emailTitle = 'Новое занятие';

    /** @var User $user */
    protected $user;

    /** @var GroupLesson $lesson */
    protected $lesson;

    public function __construct(User $user, GroupLesson $lesson)
    {
        $this->user = $user;
        $this->lesson = $lesson;
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
            'lessonType' => $this->lesson->getLessonType()->getName(),
            'time' => $this->lesson->getDate(),
            'comment' => $this->lesson->getComment(),
            'lessonsName' => $this->lesson->getName()
        ];
    }

    public function getParametersForEmail(): ?array
    {
        return $this->paramForEmail;
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
