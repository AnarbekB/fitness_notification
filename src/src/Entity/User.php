<?php

namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    use RequiredFieldsEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();

        $this->createdAt = \DateTimeImmutable::createFromMutable(new \DateTime());
        $this->updatedAt = new \DateTime();
    }
}