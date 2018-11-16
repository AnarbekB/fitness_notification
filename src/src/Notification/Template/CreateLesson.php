<?php

namespace App\Notification\Template;

use App\Constants\ChannelNotification;
use App\Entity\GroupLesson;
use App\Entity\User;

class CreateLesson extends Notification
{
    protected $toEmail = false;

    protected $toSms = false;

    /** @var  string | null */
    protected $smsText = '{name}, занятие по подписке({lessonType}): {lessonName}. Дата: {date}. Комментарий : {lessonComment}. Тренер: {trainer}';

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
        $this->setSmsText();
        if ($user->getChannelNotification() == ChannelNotification::EMAIL()->getValue()) {
            $this->toEmail = true;
        }
        if ($user->getChannelNotification() == ChannelNotification::PHONE()->getValue()) {
            $this->toSms = true;
        }
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
            'lessonsName' => $this->lesson->getName(),
            'trainer' => $this->lesson->getTrainer()->getFullName()
        ];
    }

    public function getParametersForEmail(): ?array
    {
        return $this->paramForEmail;
    }

    public function setSmsText()
    {
        $this->smsText = placeholders_replace(
            $this->smsText,
            [
                'name' => $this->user->getFirstName(),
                'lessonName' => $this->lesson->getName(),
                'date' => $this->lesson->getDate()->format('Y-m-d H:i:s'),
                'lessonComment' => $this->lesson->getComment(),
                'lessonType' => $this->lesson->getLessonType()->getName(),
                'trainer' => $this->lesson->getTrainer()->getFullName()
            ]
        );
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
