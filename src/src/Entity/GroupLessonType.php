<?php

namespace App\Entity;

use App\Entity\EntityTraits\Activity;
use App\Entity\EntityTraits\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class GroupLessonType
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\GroupLessonTypeRepository")
 * @ORM\Table(name="group_lesson_types")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"code"}, message="Занятие с указанным кодом уже существует")
 */
class GroupLessonType
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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $comment;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $image;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="User", inversedBy="groupLessonsType")
     */
    protected $users;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="GroupLesson", mappedBy="lessonType")
     */
    protected $lessons;

    /**
     * @var UploadedFile
     */
    protected $file;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->lessons = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function removeUser(User $user): bool
    {
        return $this->users->removeElement($user);
    }

    /**
     * @param User $user
     * @return GroupLessonType
     */
    public function addUser(User $user): GroupLessonType
    {
        $this->users[] = $user;
        return $this;
    }

    //todo move file upload func to upload service
    /**
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->image ? null : $this->getUploadRootDir(kernel_path()).'/'.$this->image;
    }

    /**
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->image ? null : $this->getUploadDir().'/'.$this->image;
    }

    /**
     * @param string $basepath
     * @return string
     */
    protected function getUploadRootDir($basepath = '/')
    {
        // the absolute directory path where uploaded documents should be saved
        return $basepath . $this->getUploadDir();
    }

    /**
     * @return string
     */
    protected function getUploadDir()
    {
        //todo this value in config
        return 'uploads/group_lessons';
    }

    /**
     * @param string $fileName
     * @return $this
     */
    protected function setImage(string $fileName)
    {
        $this->image = $fileName;

        return $this;
    }

    /**
     * @return string | null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Upload file
     *
     * @param string $basepath
     */
    public function upload(string $basepath)
    {
        if (null === $this->file) {
            return;
        }

        if (null === $basepath) {
            return;
        }

        $this->file->move($this->getUploadRootDir($basepath), $this->file->getClientOriginalName());

        $this->setImage($this->file->getClientOriginalName());

        $this->file = null;
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param UploadedFile|null $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
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
     * @return string | null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code)
    {
        $this->code = $code;
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
     * @param GroupLesson $lesson
     * @return GroupLessonType
     */
    public function addLesson(GroupLesson $lesson): GroupLessonType
    {
        $this->lessons[] = $lesson;
        return $this;
    }

    /**
     * @param GroupLesson $lesson
     * @return bool
     */
    public function removeLesson(GroupLesson $lesson): bool
    {
        return $this->lessons->removeElement($lesson);
    }

    /**
     * @return Collection
     */
    public function getLessons(): Collection
    {
        return $this->lessons;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return sprintf(
            '%s %s',
            $this->getName() ? $this->getName() : '',
            $this->getCode() ? $this->getCode() : ''
        );
    }
}
