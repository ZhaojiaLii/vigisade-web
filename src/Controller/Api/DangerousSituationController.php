<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Entity\DangerousSituation;
use App\Entity\TypeDangerousSituation;
use App\Repository\DangerousSituationRepository;
use App\Repository\TypeDangerousSituationRepository;
use App\Service\UploadImageBase64;
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
     * @return \FOS\RestBundle\View\View|JsonResponse
     * @throws \Exception
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data)) {
            return new JsonResponse(['code' => '400', 'message' => "The JSON sent contains invalid data or empty"], 400);
        }

        // check all parameters mandatory in data
        $dataKeys =  ['typeSituationDangerousID', 'dangerousSituationComment'];

        foreach ($dataKeys as $key){
            if(!array_key_exists($key, $data)) {
                return new JsonResponse(['code' => '400', 'message' => "The parameter `".$key."` should be specified."], 400);
            }
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

    public function getHistory(Request $request, EntityManagerInterface $em){
        // GET the user Role
        $userRole = $this->getUser()->getRoles();

        $dangerousSituations = [];

        //If the user has a  ROLE_CONDUCTEUR => we show only his Dangerouse Situation
        if($userRole[0] === 'ROLE_CONDUCTEUR'){
            $dangerousSituations = $this->em
                ->getRepository(DangerousSituation::class)
                ->findBy(['user' => $this->getUser()]);

            if (!$dangerousSituations) {
                $message = [
                    'code' => '200',
                    'message' => "This user dont have Dangerouse Situation"
                ];

                return new JsonResponse($message, 200);
            }
        }

        //If the user has a ROLE_MANAGER => we show his dangerous situation and the dangerous situation related to his entity (Agence)
        if($userRole[0] === 'ROLE_MANAGER'){

            if ($this->getUser()->getEntity() === null) {
                $message = [
                    'code' => '200',
                    'message' => "this user is not related to any entity"
                ];

                return new JsonResponse($message, 200);
            }

            $dangerousSituations = $this->em
                ->getRepository(DangerousSituation::class)
                ->findBy(['entity' => $this->getUser()->getEntity()]);

            if (!$dangerousSituations) {
                $message = [
                    'code' => '200',
                    'message' => "This user dont have Dangerouse Situation"
                ];

                return new JsonResponse($message, 200);
            }
        }

        //If the user has a ROLE_ADMIN => we show  dangerous situation related to his direction (direction)
        if($userRole[0] === 'ROLE_ADMIN'){

            // if the user has a direction = null
            if ($this->getUser()->getDirection() === null) {
                $message = [
                    'code' => '200',
                    'message' => "this user is not related to any Direction"
                ];

                return new JsonResponse($message, 200);
            }

            $dangerousSituations = $this->em
                ->getRepository(DangerousSituation::class)
                ->findBy(['direction' => $this->getUser()->getDirection()]);

            if (!$dangerousSituations) {
                $message = [
                    'code' => '200',
                    'message' => "Dangerouse Situation not found"
                ];

                return new JsonResponse($message, 200);
            }
        }

        $responseArray = [];
        foreach($dangerousSituations as $dangerousSituation){
            $responseArray[] = [
                "DangerousSituationId" => $dangerousSituation->getId() ,
                "DangerousSituationTypeDangerousSituation" => $dangerousSituation->getTypeDangerousSituation() ? $dangerousSituation->getTypeDangerousSituation()->getId() : null,
                "DangerousSituationDirection" => $dangerousSituation->getDirection() ? $dangerousSituation->getDirection()->getId() : null,
                "DangerousSituationArea" => $dangerousSituation->getArea() ? $dangerousSituation->getArea()->getId() : null,
                "DangerousSituationEntity" => $dangerousSituation->getEntity() ? $dangerousSituation->getEntity()->getId() : null,
                "DangerousSituationUser" => $dangerousSituation->getUser() ? $dangerousSituation->getUser()->getId() : null,
                "DangerousSituationFirstName" => $dangerousSituation->getUser() ? $dangerousSituation->getUser()->getFirstname() : null,
                "DangerousSituationLastName" => $dangerousSituation->getUser() ? $dangerousSituation->getUser()->getLastname() : null,
                "DangerousSituationDate" => $dangerousSituation->getDate(),
                "DangerousSituationComment" => $dangerousSituation->getComment(),
                "DangerousSituationPhoto" => $dangerousSituation->getPhoto(),
            ];
        }

        return $this->createResponse('DangerousSituation', $responseArray);
    }
}
