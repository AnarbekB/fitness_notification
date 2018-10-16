<?php

namespace App\Service;

use App\Entity\User;
use App\Notification\Template\Notification;
use Symfony\Component\Templating\EngineInterface;

class NotifyService
{
    /** @var EngineInterface  */
    protected $template;

    /** @var \Swift_Mailer */
    protected $mailer;

    /** @var TwilioSmsService $smsService */
    protected $smsService;

    public function __construct(
        EngineInterface $twigEngine,
        \Swift_Mailer $mailer,
        TwilioSmsService $smsService
    ) {
        $this->template = $twigEngine;
        $this->mailer = $mailer;
        $this->smsService = $smsService;
    }

    public function notify(Notification $template)
    {
        if ($template->isToSms()) {
            $this->toSms($template->getUser(), $template);
        }
        if ($template->isToEmail()) {
            $this->toEmail($template->getUser(), $template);
        }
    }

    private function toSms(User $user, Notification $template)
    {
        $this->smsService->send($user->getPhone(), $template->getSmsText());
    }

    private function toEmail(User $user, Notification $template)
    {
        $message = (new \Swift_Message($template->getEmailTitle()))
            ->setFrom(getenv('MAILER_FROM'))
            ->setTo($user->getEmail())
            ->setBody(
                $this->template->render(
                    $template->getPathToEmailTemplate(),
                    $template->getParametersForEmail()
                ),
                'text/html'
            );

        $result = $this->mailer->send($message);

        return $result;
    }
}
