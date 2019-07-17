<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Entity\User;
use App\Exception\Http\NotFoundException;
use App\Repository\AreaRepository;
use App\Repository\DirectionRepository;
use App\Repository\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends ApiController
{
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
        EntityManagerInterface $em,
        DirectionRepository $directionRepository,
        AreaRepository $areaRepository,
        EntityRepository $entityRepository
    ) {
        $this->em = $em;
        $this->directionRepository = $directionRepository;
        $this->areaRepository = $areaRepository;
        $this->entityRepository = $entityRepository;
    }

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
     * @param Request $request
     * @return JsonResponse
     */
    public function updateUser(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data)) {
            throw new NotFoundException("Data empty");
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
            throw new NotFoundException("data empty");
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setfirstname($data['firstname']);
        $user->setPassword($data['password']);
        $user->setImage($data['image']);
        $user->setActif(true);
        $this->em->persist($user);
        $this->em->flush();

        return new JsonResponse(null, 200);
    }
}
