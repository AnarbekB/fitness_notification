<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class MenuRepository extends EntityRepository
{
    public function getMainMenu()
    {
        return $this->findBy(['typeMenu' => 1]);//todo ref it or delete
    }
}
