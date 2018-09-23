<?php

namespace App\Entity;

use App\Constants\Gender;
use App\Constants\NotifyTemplate;
use App\Entity\EntityTraits\Timestamp;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Twilio\Rest\Client;

/**
 * @ORM\Entity
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
    public function getPasswordResetGuid(): string
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
     * @return string | null
     */
    public function getProfilePhoto(): ?string
    {
        return $this->profilePhoto;
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

        $this->setImageName($this->file->getClientOriginalName());

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

    public function __toString()
    {
        return sprintf('%s', $this->getFullName());
    }

    public function getFullName() :string
    {
        return $this->firstName . ' ' . $this->middleName . ' ' . $this->lastName;
    }

    public function notifySms(NotifyTemplate $template)
    {
        $client = new Client(getenv('TWILIO_SID'), getenv('TWILIO_TOKEN'));

        $client->messages->create(
            $this->phone,
            [
                'from' => getenv('TWILIO_FROM'),
                'body' => placeholders_replace(
                    $template->getValue(),
                    [
                        'fullName' => $this->getFullName(),
                        'linkSetPassword' =>
                            'http://fitness.notification.local:8083/reset-password/' . $this->getPasswordResetGuid()
                    ]
                )
            ]
        );
    }

    public function notify(object $instance)
    {
        $instance->send();
    }
}
