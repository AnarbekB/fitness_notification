<?php

namespace App\Entity\EntityTraits;

use Doctrine\ORM\Mapping as ORM;

trait Activity
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", options={"default":true})
     */
    private $active = true;

    public function setActive($active)
    {
        $this->active = (boolean) $active;
        return $this;
    }

    public function getActive()
    {
        return $this->active;
    }
}
