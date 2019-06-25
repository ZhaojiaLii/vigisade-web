<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Entity\Header;
use App\Exception\Http\NotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class HeaderController extends ApiController
{
    public function getHeader(EntityManagerInterface $em)
    {
        $header = $em->getRepository(Header::class)->findAll();

        if (!$header) {
            throw new NotFoundException("Header and News empty.");
        }

        return $this->createResponse('HEADER', $header);
    }
}
