<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Entity\DangerousSituation;
use App\Entity\TypeDangerousSituation;
use App\Repository\DangerousSituationRepository;
use App\Repository\TypeDangerousSituationRepository;
use JMS\Serializer\SerializerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class DangerousSituationController extends ApiController
{
    private $em;
    private $typeDangerousSituationRepository;
    private $dangerousSituationRepository;

    public function __construct (
        EntityManagerInterface $em,
        TypeDangerousSituationRepository $typeDangerousSituationRepository,
        DangerousSituationRepository $dangerousSituationRepository)
    {
        $this->em = $em;
        $this->typeDangerousSituationRepository = $typeDangerousSituationRepository;
        $this->dangerousSituationRepository = $dangerousSituationRepository;
    }

    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $data = json_decode($request->getContent(), true);

        $dangerouseSituation = new DangerousSituation();
        $dangerouseSituation->setDate( new \DateTime());
        $dangerouseSituation->setPhoto($data['dangerousSituationPhoto']);
        $dangerouseSituation->setComment($data['dangerousSituationComment']);
        $dangerouseSituation->setUser($this->getUser());
        $dangerouseSituation->setDirection($this->getUser()->getDirection());
        $dangerouseSituation->setArea($this->getUser()->getArea());
        $dangerouseSituation->setEntity($this->getUser()->getEntity());

        $dangerouseSituation->setTypeDangerousSituation($this->typeDangerousSituationRepository->find($data['typeSituationDangerousID']));

        $em->persist($dangerouseSituation);
        $em->flush();

        $response = $this->dangerousSituationRepository->getDangerousSituationByID($dangerouseSituation->getId());

        return $this->createResponse('DangerousSituation', $response);
    }
}
