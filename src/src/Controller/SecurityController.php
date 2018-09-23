<?php

namespace App\Controller;

use App\Manager\SecurityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractController
{
    /** @var SecurityManagerInterface */
    private $securityManager;

    /**
     * SecurityController constructor.
     * @param SecurityManagerInterface $securityManager
     */
    public function __construct(SecurityManagerInterface $securityManager)
    {
        $this->securityManager = $securityManager;
    }

    /**
     * @param string $slug
     * @param Request $request
     * @return Response
     */
    public function resetUrl(string $slug, Request $request): Response
    {
        $passwordObject = $this->securityManager->changePasswordByResetGuid($slug, $request);

        if ($passwordObject->success) {
            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'fitness/security/reset_password.html.twig',
            [
                'form' => $passwordObject->form->createView()
            ]
        );
    }
}
