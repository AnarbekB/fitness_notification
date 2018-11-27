<?php

namespace App\DataFixtures;

use App\Entity\GroupLessonType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GroupLessonTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $groupLessonTypePark = new GroupLessonType();
        $groupLessonTypePark->setName('Уличные занятия в парках');
        $groupLessonTypePark->setCode('park-lesson');
        $groupLessonTypePark->setComment('Занятия проводятся в различных парках города');
        $groupLessonTypePark->setImage('img/park-lesson.jpg');
        $manager->persist($groupLessonTypePark);

        $groupLessonTypeCrossFit = new GroupLessonType();
        $groupLessonTypeCrossFit->setName('Занятия кросфитом на стадионе');
        $groupLessonTypeCrossFit->setCode('crossfit');
        $groupLessonTypeCrossFit->setComment('Кросфит на стадионе Динамо');
        $groupLessonTypeCrossFit->setImage('img/workout.jpg');
        $manager->persist($groupLessonTypeCrossFit);

        $groupLessonTypeWalks = new GroupLessonType();
        $groupLessonTypeWalks->setName('Прогулки по лесу');
        $groupLessonTypeWalks->setCode('wood');
        $groupLessonTypeWalks->setComment('Замечательные прогулки по красивому лесу');
        $groupLessonTypeWalks->setImage('img/wood.jpg');
        $manager->persist($groupLessonTypeWalks);

        $manager->flush();
    }
}
