<?php

namespace App\Doctrine;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\CanonicalFieldsUpdater;
use FOS\UserBundle\Util\PasswordUpdaterInterface;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserManager
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    public function __construct(
        PasswordUpdaterInterface $passwordUpdater,
        CanonicalFieldsUpdater $canonicalFieldsUpdater,
        EncoderFactoryInterface $encoderFactory,
        ObjectManager $om
    ) {

        $this->objectManager = $om;
        $this->encoderFactory = $encoderFactory;
    }

    public function findUserByGuid(string $guid)
    {
        return $this->getRepository()->findOneBy(['passwordResetGuid' => $guid]);
    }

    public function updateUser(UserInterface $user, $andFlush = true)
    {
        $this->hashPassword($user);

        $this->objectManager->persist($user);
        if ($andFlush) {
            $this->objectManager->flush();
        }
    }

    public function hashPassword(UserInterface $user)
    {
        $plainPassword = $user->getPlainPassword();

        if (0 === strlen($plainPassword)) {
            return;
        }

        $encoder = $this->encoderFactory->getEncoder($user);

        if ($encoder instanceof BCryptPasswordEncoder) {
            $user->setSalt(null);
        } else {
            $salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
            $user->setSalt($salt);
        }

        $hashedPassword = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($hashedPassword);
        $user->eraseCredentials();
    }

    protected function getRepository()
    {
        return $this->objectManager->getRepository(User::class);
    }
}
