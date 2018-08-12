<?php

namespace App\Entity;

use App\Constants\Gender;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected $firstName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected $middleName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected $lastName;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dateOfBirth;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $gender;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $profilePhoto;

    /**
     * @var UploadedFile
     */
    protected $file;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $phone;

    public function __construct()
    {
        parent::__construct();

        $this->createdAt = \DateTimeImmutable::createFromMutable(new \DateTime());
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return void
     */
    public function prePersist(): void
    {
        $this->createdAt = \DateTimeImmutable::createFromMutable(new \DateTime());
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return void
     */
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param string $firstName
     * @return User
     */
    public function setFirstName(string $firstName): User
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $middleName
     * @return User
     */
    public function setMiddleName(string $middleName): User
    {
        $this->middleName = $middleName;
        return $this;
    }

    /**
     * @return string
     */
    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    /**
     * @param string $lastName
     * @return User
     */
    public function setLastName(string $lastName): User
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return \DateTime
     */
    public function getDateOfBirth(): ?\DateTime
    {
        return $this->dateOfBirth;
    }

    /**
     * @param \DateTime $dateOfBirth
     * @return User
     */
    public function setDateOfBirth(\DateTime $dateOfBirth): User
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    /**
     * @return string
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param Gender $gender
     * @return User
     */
    public function setGender(Gender $gender): User
    {
        $this->gender = $gender->getValue();
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone)
    {
        $this->phone = format_phone($phone);
    }

    /**
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->profilePhoto ? null : $this->getUploadRootDir(kernel_path()).'/'.$this->profilePhoto;
    }

    /**
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->profilePhoto ? null : $this->getUploadDir().'/'.$this->profilePhoto;
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
        return 'uploads/users';
    }

    /**
     * @param string $fileName
     * @return $this
     */
    protected function setImageName(string $fileName)
    {
        $this->profilePhoto = $fileName;

        return $this;
    }

    /**
     * @param string $basepath
     */
    public function upload(string $basepath)
    {
        // the file property can be empty if the field is not required
        if (null === $this->file) {
            return;
        }

        if (null === $basepath) {
            return;
        }

        // we use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the target filename to move to
        $this->file->move($this->getUploadRootDir($basepath), $this->file->getClientOriginalName());

        // set the path property to the filename where you'ved saved the file
        $this->setImageName($this->file->getClientOriginalName());

        // clean up the file property as you won't need it anymore
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
}
