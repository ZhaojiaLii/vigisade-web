<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Exception\Http\NotFoundException;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class UserController extends ApiController
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * Gets information about the current user.
     *
     * @return \App\Entity\User|\FOS\RestBundle\View\View
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUser()
    {
        $user = ApiController::getUser();
        $userArray = [
            'mail' => $user->getEmail(),
            'directionId' => $user->getDirection() ? $user->getDirection()->getId() : null,
            'areaId' => $user->getArea() ? $user->getArea()->getId() : null,
            'entityId' => $user->getEntity() ? $user->getEntity()->getId() : null,
            'language' => 'FR',
            'firstName' => $user->getFirstname(),
            'lastName' => $user->getLastname(),
            'photo' => $user->getImage(),
            'countRemainingActions' => $this->userRepository->getCountRemainingActions($user->getId()),
            'countCurrentMonthVisits' => $this->userRepository->getCountCurrentMonthVisits($user->getId()),
            'countLastMonthVisits' => $this->userRepository->getCountLastMonthVisits($user->getId())
        ];

        return $this->createResponse('User', $userArray);
    }

    /**
     * Updates information of the current user.
     */
    public function updateUser()
    {
        /*
         * @todo: checks payload data are correct.
         *      - App\Service\Validator\ApiPayload\UserValidator
         *      - App\Repository\UserRepository
         */

        /*
         * @todo: update the current user.
         *      - App\Repository\UserRepository
         */

        /*
         * @todo: returns the current user.
         */

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    public function createUser()
    {
        /*
         * @todo: checks payload data are correct.
         *      - App\Service\Validator\ApiPayload\UserValidator
         */

        /*
         * @todo: create a new user.
         *      - App\Repository\UserRepository
         */

        /*
         * @todo: returns the created user.
         */

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
