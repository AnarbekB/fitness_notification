<?php

namespace App\Manager;

use App\Doctrine\UserManager;
use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Object\PasswordObject;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\UserManipulator;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SecurityManager implements SecurityManagerInterface
{
    use ControllerTrait;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var UserManipulator
     */
    private $userManager;

    private $controller;

    public function __construct(
        UserManager $userManager,
        ContainerInterface $container
    ) {
        $this->userManager = $userManager;
        $this->container = $container;
    }

    /**
     * @param string $guid
     * @param Request $request
     * @return PasswordObject
     */
    public function changePasswordByResetGuid(string $guid, Request $request) :PasswordObject
    {
        /** @var User $user */
        $user = $this->userManager->findUserByGuid($guid);
        if (null == $user) {
            throw new NotFoundHttpException(
                'User not found'
            );
        }

        $form = $this->createForm(ChangePasswordFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getNormData();
            /** @var UserInterface $user */
            $user = $this->userManager->findUserByGuid($guid);

            $user->setPlainPassword($password);
            $user->setEnabled(true);
            $this->userManager->updateUser($user);

            return PasswordObject::fromChangePassword($form, true);
        }

        return PasswordObject::fromChangePassword($form, false);
    }
}
