<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Entity\DangerousSituation;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class DangerousSituationController extends ApiController
{
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $data = $request->getContent();
        $dangerouseSituation = $this->serializer->deserialize($data, 'App\Entity\DangerousSituation', 'json');
        $dangerouseSituation->setDirection($this->getUser()->getDirection());
        $dangerouseSituation->setZone($this->getUser()->getArea());
        $dangerouseSituation->setEntity($this->getUser()->getEntity());

        $em->persist($dangerouseSituation);
        $em->flush();

        return new Response('Dangerouse Situation resource created', Response::HTTP_CREATED);
    }
}
