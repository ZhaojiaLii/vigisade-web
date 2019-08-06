<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Entity\DangerousSituation;
use App\Entity\TypeDangerousSituation;
use App\Repository\DangerousSituationRepository;
use App\Repository\TypeDangerousSituationRepository;
use App\Service\UploadImageBase64;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class DangerousSituationController extends ApiController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TypeDangerousSituationRepository
     */
    private $typeDangerousSituationRepository;

    /**
     * @var DangerousSituationRepository
     */
    private $dangerousSituationRepository;

    /**
     * @var UploadImageBase64
     */
    private $uploadImageBase64;

    /**
     * DangerousSituationController constructor.
     * @param EntityManagerInterface $em
     * @param TypeDangerousSituationRepository $typeDangerousSituationRepository
     * @param DangerousSituationRepository $dangerousSituationRepository
     * @param UploadImageBase64 $uploadImageBase64
     */
    public function __construct (
        EntityManagerInterface $em,
        TypeDangerousSituationRepository $typeDangerousSituationRepository,
        DangerousSituationRepository $dangerousSituationRepository,
        UploadImageBase64 $uploadImageBase64)
    {
        $this->em = $em;
        $this->typeDangerousSituationRepository = $typeDangerousSituationRepository;
        $this->dangerousSituationRepository = $dangerousSituationRepository;
        $this->uploadImageBase64 = $uploadImageBase64;
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     * @return \FOS\RestBundle\View\View|JsonResponse
     * @throws \Exception
     */
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data)) {
            $message = ['message' => 'The JSON sent contains invalid data or empty'];

            return new JsonResponse($message, 400);
        }

        $dangerouseSituation = new DangerousSituation();
        $dangerouseSituation->setDate( new \DateTime());

        // get picture with format Base64
        $imageBase64 = $data['dangerousSituationPhoto'];

        //get the path to store image result from service.yaml
        $path = $this->getParameter('app.path.dangerous_situation_images');

        //this service return the name of picture if uploaded true and return false if picture not uploaded
        $imageBase64 = $this->uploadImageBase64->UploadImage($imageBase64, __DIR__.'/../../../'.$path);

        if($imageBase64){
            $dangerouseSituation->setPhoto($imageBase64);
        }

        $dangerouseSituation->setComment($data['dangerousSituationComment']);
        $dangerouseSituation->setUser($this->getUser());
        $dangerouseSituation->setDirection($this->getUser() ? $this->getUser()->getDirection() : null);
        $dangerouseSituation->setArea($this->getUser() ? $this->getUser()->getArea() : null );
        $dangerouseSituation->setEntity($this->getUser() ? $this->getUser()->getEntity() : null );

        $dangerouseSituation->setTypeDangerousSituation($this->typeDangerousSituationRepository->find($data['typeSituationDangerousID']));

        $em->persist($dangerouseSituation);
        $em->flush();

        $response = $this->dangerousSituationRepository->getDangerousSituationByID($dangerouseSituation->getId());

        return $this->createResponse('DangerousSituation', $response);
    }
}
