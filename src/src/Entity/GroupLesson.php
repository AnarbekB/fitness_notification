<?php

namespace App\Entity;

use App\Entity\EntityTraits\Activity;
use App\Entity\EntityTraits\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class GroupLessons
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="group_lessons")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"name"}, message="Занятие с указанным названием уже существует")
 */
class GroupLesson
{
    use Activity;
    use Timestamp;

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $comment;

    protected $trainer;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="User", inversedBy="groupLessons")
     */
    protected $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
}
