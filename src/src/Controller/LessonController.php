<?php

namespace App\Controller;

use App\Entity\GroupLessonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LessonController extends AbstractController
{

    /**
     * @return Response
     */
    public function groupListAction(): Response
    {
        $groupLessonTypesRepository = $this->getDoctrine()->getRepository(GroupLessonType::class);
        $groupLessonTypes = $groupLessonTypesRepository->findBy(['active' => 1]);
        $user = $this->getUser();

        return $this->render(
            'fitness/lessons/group_lessons_list.html.twig',
            [
                'user' => $user,
                'lessonTypes' => $groupLessonTypes
            ]
        );
    }

    /**
     * @param string $code
     * @param string $redirectRoute
     * @return RedirectResponse
     */
    public function subscribeLessonTypeAction(string $code, string $redirectRoute)
    {
        //todo move this to security access_control
        //like this - { path: ^/group-lessons/[*]+/subscribe$, roles: ROLE_USER }
        //todo check for a valid subscription
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();

        $groupLessonTypesRepository = $this->getDoctrine()->getRepository(GroupLessonType::class);
        /** @var GroupLessonType $groupLessonType */
        $groupLessonType = $groupLessonTypesRepository->findOneBy(['code' => $code]);

        if (null == $groupLessonType) {
            throw new NotFoundHttpException('Lesson not found.');
        }

        $groupLessonType->addUser($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute($redirectRoute);
    }

    /**
     * @param string $code
     * @param string $redirectRoute
     * @return RedirectResponse
     */
    public function unSubscribeLessonTypeAction(string $code, string $redirectRoute)
    {
        //todo move this to security access_control
        //like this - { path: ^/group-lessons/[*]+/subscribe$, roles: ROLE_USER }
        //todo check for a valid subscription
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();

        $groupLessonTypesRepository = $this->getDoctrine()->getRepository(GroupLessonType::class);
        /** @var GroupLessonType $groupLessonType */
        $groupLessonType = $groupLessonTypesRepository->findOneBy(['code' => $code]);

        if (null == $groupLessonType) {
            throw new NotFoundHttpException('Lesson not found.');
        }

        $groupLessonType->removeUser($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute($redirectRoute);
    }
}
