<?php

namespace App\Entity;

use App\Constants\ChannelNotification;
use App\Constants\Gender;
use App\Entity\EntityTraits\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
{
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
     * @ORM\Column(type="string", nullable=true)
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

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $passwordResetGuid;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $channelNotification = ChannelNotification::EMAIL;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="GroupLessonType", mappedBy="users")
     */
    protected $groupLessonsType;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->groupLessonsType = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize([
            $this->password,
            $this->salt,
            $this->usernameCanonical,
            $this->username,
            $this->enabled,
            $this->id,
            $this->email,
            $this->emailCanonical,
            $this->phone,
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        if (13 === count($data)) {
            // Unserializing a User object from 1.3.x
            unset($data[4], $data[5], $data[6], $data[9], $data[10]);
            $data = array_values($data);
        } elseif (11 === count($data)) {
            // Unserializing a User from a dev version somewhere between 2.0-alpha3 and 2.0-beta1
            unset($data[4], $data[7], $data[8]);
            $data = array_values($data);
        }

        list(
            $this->password,
            $this->salt,
            $this->usernameCanonical,
            $this->username,
            $this->enabled,
            $this->id,
            $this->email,
            $this->emailCanonical,
            $this->phone,
            ) = $data;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return string
     */
    public function getPasswordResetGuid(): ?string
    {
        return $this->passwordResetGuid;
    }

    /**
     * @param string $passwordResetGuid
     */
    public function setPasswordResetGuid(string $passwordResetGuid)
    {
        $this->passwordResetGuid = $passwordResetGuid;
    }

    /**
     * @return Collection
     */
    public function getGroupLessonsType(): Collection
    {
        return $this->groupLessonsType;
    }

    /**
     * @return string
     */
    public function getChannelNotification(): ?string
    {
        return $this->channelNotification;
    }

    /**
     * @param ChannelNotification $channelNotification
     * @return User
     */
    public function setChannelNotification(ChannelNotification $channelNotification): User
    {
        $this->channelNotification = $channelNotification->getValue();

        return $this;
    }

    /**
     * @param string $fileName
     * @return $this
     */
    public function setProfilePhoto(string $fileName)
    {
        $this->profilePhoto = $fileName;

        return $this;
    }

    /**
     * @return string | null
     */
    public function getProfilePhoto(): ?string
    {
        return $this->profilePhoto;
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

    public function __toString()
    {
        return sprintf('%s', $this->getFullName());
    }

    public function getFullName() :string
    {
        return $this->lastName. ' ' . $this->firstName . ' ' . $this->middleName;
    }

    public function isCanGetNotification(): bool
    {
        if ($this->getChannelNotification() !== ChannelNotification::NOTHING()->getValue() &&
        $this->isEnabled()
        ) {
            return true;
        }

        return false;
    }
}
