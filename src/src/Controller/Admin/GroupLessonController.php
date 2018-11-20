<?php

namespace App\Controller\Admin;

use App\Constants\ChannelNotification;
use App\Entity\GroupLesson;
use App\Entity\User;
use App\Notification\Template\CustomMessage;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;

class GroupLessonController extends CRUDController
{
    /** @var  Producer */
    protected $producerMQ;

    protected function configure()
    {
        parent::configure();

        $this->producerMQ = $this->get('old_sound_rabbit_mq.notification_producer');
    }

    public function sendSmsAction(Request $request, int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $groupLessonRepository = $em->getRepository(GroupLesson::class);
        /** @var GroupLesson $groupLesson */
        $groupLesson = $groupLessonRepository->findOneBy(['id' => $id]);

        $text = $request->get('sms_text');
        preg_match_all('#\{(.*?)\}#', $text, $placeholders);

        $users = $groupLesson->getLessonType()->getUsers();
        $template = null;
        /** @var User $user */
        foreach ($users as $user) {
            if ($user->isCanGetNotification()) {
                $template = new CustomMessage($user, $groupLesson, $placeholders[1], $text);
                $this->producerMQ->publish(serialize($template));
            }
        }

        return $this->redirectToRoute('admin_app_grouplesson_show', ['id' => $id]);
    }

    public function sendEmailAction(Request $request, int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $groupLessonRepository = $em->getRepository(GroupLesson::class);
        /** @var GroupLesson $groupLesson */
        $groupLesson = $groupLessonRepository->findOneBy(['id' => $id]);

        $text = $request->get('email_text');
        preg_match_all('#\{(.*?)\}#', $text, $placeholders);

        $users = $groupLesson->getLessonType()->getUsers();
        $template = null;
        /** @var User $user */
        foreach ($users as $user) {
            if ($user->isCanGetNotification()) {
                $template = new CustomMessage($user, $groupLesson, null, null, $placeholders[1], $text);
                $this->producerMQ->publish(serialize($template));
            }
        }

        return $this->redirectToRoute('admin_app_grouplesson_show', ['id' => $id]);
    }
}
