<?php

namespace App\Controller;

use App\Entity\User;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiController extends AbstractFOSRestController
{
    protected function createResponse(string $apiGroup, $data)
    {
        return (new View($data, Response::HTTP_OK))
            ->setContext((new Context())->setGroups([$apiGroup]));
    }

    /**
     * @return User
     */
    protected function getUser()
    {
        return parent::getUser();
    }
}
