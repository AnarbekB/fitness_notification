<?php

namespace App\DataFixtures;

use App\Entity\Menu;
use App\Entity\TypeMenu;
use App\Repository\TypeMenuRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class MenuFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $lesson = new Menu();

        /** @var TypeMenuRepository $typeMenuRepository */
        $typeMenuRepository = $manager->getRepository(TypeMenu::class);
        /** @var TypeMenu $mainTypeMenu */
        $mainTypeMenu = $typeMenuRepository->findOneBy(['code' => 'main']);

        $lesson->setName('Занятия');
        $lesson->setRoute('#');
        $lesson->setTypeMenu($mainTypeMenu);
        $lesson->setStatic(false);
        $manager->persist($lesson);

        $groupLessons = new Menu();
        $groupLessons->setName('Групповые');
        $groupLessons->setRoute('group-lessons');
        $groupLessons->setParent($lesson);
        $groupLessons->setTypeMenu($mainTypeMenu);
        $groupLessons->setStatic(false);
        $manager->persist($groupLessons);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            MenuTypeFixtures::class
        ];
    }
}
