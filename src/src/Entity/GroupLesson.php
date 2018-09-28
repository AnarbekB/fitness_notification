<?php

namespace App\Entity;

use App\Entity\EntityTraits\Activity;
use App\Entity\EntityTraits\Timestamp;
use Doctrine\ORM\Mapping as ORM;
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
     * @var GroupLessonType
     *
     * @ORM\ManyToOne(targetEntity="GroupLessonType", inversedBy="lessons")
     */
    protected $lessonType;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $comment;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $trainer;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string | null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return \DateTime | null
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return string | null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return User | null
     */
    public function getTrainer(): ?User
    {
        return $this->trainer;
    }

    /**
     * @param User $trainer
     */
    public function setTrainer(User $trainer)
    {
        $this->trainer = $trainer;
    }

    /**
     * @return GroupLessonType | null
     */
    public function getLessonType(): ?GroupLessonType
    {
        return $this->lessonType;
    }

    /**
     * @param GroupLessonType $type
     */
    public function setLessonType(GroupLessonType $type)
    {
        $this->lessonType = $type;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%s %s',
            $this->getName() ? $this->getName() : '',
            $this->getDate() ? $this->getDate()->format('d.m.Y h:m') : ''
        );
    }
}
