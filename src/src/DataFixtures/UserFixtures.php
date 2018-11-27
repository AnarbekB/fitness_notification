<?php

namespace App\DataFixtures;

use App\Constants\ChannelNotification;
use App\Constants\Gender;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();

        $admin->setUsername('balmukanovab@gmail.com');
        $admin->setUsernameCanonical('balmukanovab@gmail.com');
        $admin->setEmail('balmukanovab@gmail.com');
        $admin->setEmailCanonical('balmukanovab@gmail.com');
        $admin->setEnabled(true);
        $admin->setPassword($this->encoder->encodePassword($admin, '1234'));
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setFirstName('Иван');
        $admin->setMiddleName('Иванович');
        $admin->setLastName('Иванов');
        $admin->setDateOfBirth(new \DateTime());
        $admin->setGender(Gender::MAN());
        $admin->setPhone('+7999665521');
        $admin->setChannelNotification(ChannelNotification::EMAIL());

        $manager->persist($admin);
        $manager->flush();
    }
}
