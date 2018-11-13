<?php

namespace App\Repository;

use App\Entity\TypeMenu;
use Doctrine\ORM\EntityRepository;

class TypeMenuRepository extends EntityRepository
{
    public function getMainMenu()
    {
        /** @var TypeMenu $menuType */
        $menuType = $this->findOneBy(['code' => 'main']);

        return $menuType->getMenu();
    }
}
