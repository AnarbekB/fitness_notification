<?php

namespace App\Notification\Template;

use App\Constants\ChannelNotification;
use App\Entity\GroupLesson;
use App\Entity\User;

class CustomMessage extends Notification
{
    protected $toEmail = false;

    protected $toSms = false;

    /** @var  string | null */
    protected $smsText = '';

    /** @var array | null */
    protected $paramForEmail = [];

    /** @var string */
    protected $pathToEmailTemplate = null;

    /** @var string */
    protected $emailTitle = 'All Fitness';

    /** @var string | null */
    protected $emailCustomMessage;

    /** @var User $user */
    protected $user;

    /** @var GroupLesson $lesson */
    protected $lesson;

    /**
     * CreateLesson constructor.
     * @param User $user
     * @param GroupLesson $lesson
     * @param array|null $fieldPlaceholderSms simple: ['name', 'lessonName', 'date']
     * @param string|null $smsText
     * * @param array|null $fieldPlaceholderEmail simple: ['name', 'lessonName', 'date']
     * @param string|null $emailText
     */
    public function __construct(
        User $user,
        GroupLesson $lesson,
        array $fieldPlaceholderSms = null,
        string $smsText = null,
        array $fieldPlaceholderEmail = null,
        string $emailText = null
    ) {
        $this->user = $user;
        $this->lesson = $lesson;

        //configure text and param for default message and custom massage
        $this->smsText = $smsText ?? $this->smsText;
        $this->emailCustomMessage = $emailText ? $emailText : null;

        if ($fieldPlaceholderEmail) {
            $this->setEmailCustomMessage($fieldPlaceholderEmail);
        }

        if ($fieldPlaceholderSms) {
            $this->setSmsText($fieldPlaceholderSms);
        }

        //configure channel notification for user
        if ($user->getChannelNotification() == ChannelNotification::EMAIL()->getValue() && $emailText
        ) {
            $this->toEmail = true;
        }

        if ($user->getChannelNotification() == ChannelNotification::PHONE()->getValue() && $smsText
        ) {
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

    public function setParametersForEmail(array $fields = null)
    {
        $this->paramForEmail = [];
    }

    public function getParametersForEmail(): ?array
    {
        return $this->paramForEmail;
    }

    public function setEmailCustomMessage(array $fields = null)
    {
        $this->emailCustomMessage = placeholders_replace(
            $this->emailCustomMessage,
            $this->getCustomFields($fields)
        );
    }

    public function getEmailCustomMessage(): ?string
    {
        return $this->emailCustomMessage;
    }

    public function setSmsText(array $fields = null)
    {
        $this->smsText = placeholders_replace(
            $this->smsText,
            $this->getCustomFields($fields)
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

    private function getCustomFields(?array $fields)
    {
        $resultFields = [];
        foreach ($fields as $field) {
            switch ($field) {
                case 'name':
                    $resultFields[$field] = $this->user->getFirstName();
                    break;
                case 'lessonName':
                    $resultFields[$field] = $this->lesson->getName();
                    break;
                case 'date':
                    $resultFields[$field] = $this->lesson->getDate()->format('Y-m-d H:i:s');
                    break;
                case 'lessonComment':
                    $resultFields[$field] = $this->lesson->getComment();
                    break;
                case 'lessonType':
                    $resultFields[$field] = $this->lesson->getLessonType()->getName();
                    break;
                case 'trainer':
                    $resultFields[$field] = $this->lesson->getTrainer()->getFullName();
                    break;
                default:
                    break;
            }
        }

        return $resultFields;
    }
}
