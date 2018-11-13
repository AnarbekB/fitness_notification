<?php

namespace App\DataFixtures;

use App\Entity\TypeMenu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class MenuTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $typeMenu = new TypeMenu();

        $typeMenu->setName('Главное меню');
        $typeMenu->setCode('main');

        $manager->persist($typeMenu);
        $manager->flush();
    }
}
