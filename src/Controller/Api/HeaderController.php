<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Entity\Header;
use App\Exception\Http\NotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class HeaderController extends ApiController
{
    /**
     * @param EntityManagerInterface $em
     * @return \FOS\RestBundle\View\View|JsonResponse
     */
    public function getHeader(EntityManagerInterface $em)
    {
        $header = $em->getRepository(Header::class)->findAll();

        if (!$header) {
            $message = [
                'code' => '200',
                'message' => 'Header and News empty.'];

            return new JsonResponse($message, 200);
        }

        return $this->createResponse('HEADER', $header[0]);
    }
}
