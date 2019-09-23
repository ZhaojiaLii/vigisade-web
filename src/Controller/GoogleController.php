<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GoogleController extends AbstractController
{

    private $jwtManager;

    /**
     * GoogleController constructor.
     * @param JWTTokenManagerInterface $JWTManager
     */
    public function __construct(JWTTokenManagerInterface $JWTManager)
    {
        $this->jwtManager = $JWTManager;
    }

    /**
     * Link to this controller to start the "connect" process
     *
     * @param ClientRegistry $clientRegistry
     * @return RedirectResponse
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('google')
            ->redirect();
    }

    /**
     * Google redirects to back here afterwards
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function connectCheckAction(Request $request)
    {
        if (!$this->getUser()) {
            return $this->redirect('/');
        } else {
            return new JsonResponse(['token' => $this->jwtManager->create($this->getUser())]);
        }
    }

}
