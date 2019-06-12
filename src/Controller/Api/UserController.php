<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends ApiController
{
    /**
     * Gets information about the current user.
     */
    public function getUser()
    {
        /*
         * @todo: get user object
         *      - $this->getUsername() to get current mail (defined in ApiController)
         *      - App\Repository\UserRepository
         */

        return new JsonResponse([
            'mail' => 'foobar@foo.bar',
            'direction' => 'foobar',
            'zone' => 'foobar',
            'entity' => 'foobar',
            'language' => 'foobar',
            'firstName' => 'foobar',
            'lastName' => 'foobar',
            'photo' => 'path/foobar.jpg',
            'countRemainingActions' => 1,
            'countCurrentMonthVisits' => 1,
            'countLastMonthVisits' => 1,
        ]);
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
