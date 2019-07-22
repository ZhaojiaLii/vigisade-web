<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Exception\Http\NotFoundException;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Repository\AreaRepository;
use App\Repository\DirectionRepository;
use App\Repository\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class UserController extends ApiController
{
    /** @var UserRepository */
    private $userRepository;
    
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var DirectionRepository
     */
    private $directionRepository;

    /**
     * @var AreaRepository
     */
    private $areaRepository;

    /**
     * @var EntityRepository
     */
    private $entityRepository;


    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $em,
        DirectionRepository $directionRepository,
        AreaRepository $areaRepository,
        EntityRepository $entityRepository
    ) {
        $this->em = $em;
        $this->directionRepository = $directionRepository;
        $this->areaRepository = $areaRepository;
        $this->entityRepository = $entityRepository;
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

        if (!$user) {
            $message = ['message' => 'No user found'];

            return new JsonResponse($message, 200);
        }

        $userArray = [
            'id' => $user->getId(),
            'mail' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'directionId' => $user->getDirection() ? $user->getDirection()->getId() : null,
            'areaId' => $user->getArea() ? $user->getArea()->getId() : null,
            'entityId' => $user->getEntity() ? $user->getEntity()->getId() : null,
            'language' => $user->getLanguage(),
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
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function updateUser(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data)) {
            $message = ['message' => 'Data empty'];

            return new JsonResponse($message, 200);
        }

        $user = ApiController::getUser();

        $direction = array_key_exists('direction_id', $data) ? $this->directionRepository->find($data['direction_id']) : null;
        $area = array_key_exists('area_id', $data) ? $this->areaRepository->find($data['area_id']) : null;
        $entity = array_key_exists('entity_id', $data) ? $this->entityRepository->find($data['entity_id']) : null;

        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $user->setDirection($direction);
        $user->setArea($area);
        $user->setEntity($entity);
        $user->setLanguage(array_key_exists('language', $data) ? $data['language'] : 'fr');
        $user->setImage($data['image']);

        $this->em->persist($user);
        $this->em->flush();

        return new JsonResponse([
            'id' => $user->getId(),
            'direction' => $direction ? $direction->getId() : null,
            'area' => $area ? $area->getId() : null,
            'entity' => $entity ? $entity->getId() : null

        ], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createUser(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data)) {
            $message = ['message' => 'Data empty'];

            return new JsonResponse($message, 200);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setfirstname($data['firstname']);
        $user->setPassword($data['password']);
        $user->setImage($data['image']);
        $user->setLanguage(array_key_exists('language', $data) ? $data['language'] : 'fr');
        $user->setActif(true);
        $this->em->persist($user);
        $this->em->flush();

        return new JsonResponse(null, 200);
    }
}
