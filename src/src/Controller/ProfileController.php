<?php

namespace App\Controller;

use App\Constants\ChannelNotification;
use App\Entity\User;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    /**
     * @return Response
     */
    public function showAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('@FOSUser/Profile/show.html.twig', array(
            'user' => $user,
            'group_lessons' => $user->getGroupLessonsType()
        ));
    }

    /**
     * @param string $channel
     * @return JsonResponse
     */
    public function changeChannelNotificationAction(string $channel)
    {
        /** @var User $user */
        $user = $this->getUser();

        $channel = strtoupper($channel);
        $channelNotification = ChannelNotification::$channel();

        $result = $user->setChannelNotification($channelNotification);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(['error' => $result ? false : true]);
    }
}
