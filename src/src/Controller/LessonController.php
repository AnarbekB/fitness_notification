<?php

namespace App\Controller;

use App\Entity\GroupLesson;
use App\Manager\SecurityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LessonController extends AbstractController
{

    /**
     * @param Request $request
     * @return Response
     */
    public function groupList(Request $request): Response
    {
        $groupLissonRepository = $this->getDoctrine()->getRepository(GroupLesson::class);
        $groupLessons = $groupLissonRepository->findBy(['active' => 1]);

        return $this->render(
            'fitness/lessons/group_lessons_list.html.twig',
            [
                'lessons' => $groupLessons
            ]
        );
    }
}
