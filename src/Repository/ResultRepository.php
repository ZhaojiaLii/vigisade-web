<?php

namespace App\Repository;

use App\Entity\Result;
use App\Exception\Http\NotFoundException;
use App\Repository\ResultQuestionRepository;
use App\Repository\ResultTeamMemberRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Result|null find($id, $lockMode = null, $lockVersion = null)
 * @method Result|null findOneBy(array $criteria, array $orderBy = null)
 * @method Result[]    findAll()
 * @method Result[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultRepository extends ServiceEntityRepository
{
    private $em;
    private $resultQuestionRepository;
    private $resultTeamMemberRepository;
    private $userRepository;

    /**
     * ResultRepository constructor.
     * @param RegistryInterface $registry
     * @param EntityManagerInterface $em
     * @param \App\Repository\ResultQuestionRepository $resultQuestionRepository
     * @param \App\Repository\ResultTeamMemberRepository $resultTeamMemberRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        RegistryInterface $registry,
        EntityManagerInterface $em,
        ResultQuestionRepository $resultQuestionRepository,
        ResultTeamMemberRepository $resultTeamMemberRepository,
        UserRepository $userRepository
    )
    {
        parent::__construct($registry, Result::class);
        $this->em = $em;
        $this->resultQuestionRepository = $resultQuestionRepository;
        $this->resultTeamMemberRepository = $resultTeamMemberRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param $id
     * @return object|null
     */
    public function getResultByID($id)
    {
        $result = $this->em
            ->getRepository(Result::class)
            ->find($id);

        if (!$result) {
            throw new NotFoundException("This Result with id ".$id." not exist ");
        }
        return $result;
    }

    /**
     * @param $id
     * @return array
     */
    public function getResultResponse($id)
    {
        $result = $this->em
            ->getRepository(Result::class)
            ->find($id);

        if (!$result) {
            throw new NotFoundException("This Result with id ".$id." not exist ");
        }

        $responseArray = [
            "resultId" => $result->getId(),
            "resultSurveyId" => $result->getSurvey()->getId(),
            "resultUserId" => $result->getUser()->getId(),
            "resultDirectionId" => $result->getDirection()->getId(),
            "resultAreaId" => $result->getArea()->getId(),
            "resultEntityId" => $result->getEntity()->getId(),
            "resultDate" => $result->getDate(),
            "resultPlace" => $result->getPlace(),
            "resultClient" => $result->getClient(),
            "resultValidated" => $result->getValidated(),
            "resultBestPracticeDone" => $result->getBestPracticeDone(),
            "resultBestPracticeComment" => $result->getBestPracticeComment(),
            "resultBestPracticePhoto" => $result->getBestPracticePhoto(),
            "resultTeamMember" => $this->resultTeamMemberRepository->getResultTeamMemberByResult($result->getId()),
            "resultQuestion" => $this->resultQuestionRepository->getQuestionByResult($result->getId()),
        ];

        return $responseArray;
    }

    /**
     * @param $userId
     * @return array
     */
    public function getResultUserByRole($userId)
    {
        $user = $this->userRepository->find($userId);
        $roleUser = $user->getRoles();

        $results = $this->findBy(['user' => $userId]);

        if (!$results) {
            throw new NotFoundException("This user id ".$userId." dont have a results ");
        }

        if($roleUser[0] === "ROLE_CONDUCTEUR"){

            foreach ($results as $result){

                $responseArray [] = [
                    "resultId" => $result->getId(),
                    "resultDate" => $result->getDate(),
                    "resultPlace" => $result->getPlace(),
                    "resultClient" => $result->getClient(),
                    "resultUserId" => $result->getClient(),
                    "resultValidated" => $result->getValidated(),
                ];
            }

        }else if($roleUser[0] === "ROLE_MANAGER"){

            $responseArray = $this->getResultsByEntity($user->getEntity());

        }else if($roleUser[0] === "ROLE_ADMIN"){

            $responseArray = $this->getResultsByDirection($user->getDirection());
        }

        return $responseArray;
    }

    /**
     * @param $direction
     * @return array
     */
    public function getResultsByDirection($direction)
    {
        $results = $this->findBy(['direction' => $direction]);

        if (!$results) {
            throw new NotFoundException("This direction dont have a results ".$direction);
        }

        foreach ($results as $result){
            $responseArray [] = [
                "resultId" => $result->getId(),
                "resultDirection" => $result->getDirection()->getId(),
                "resultDate" => $result->getDate(),
                "resultPlace" => $result->getPlace(),
                "resultClient" => $result->getClient(),
                "resultUserId" => $result->getClient(),
                "resultValidated" => $result->getValidated(),
            ];
        }

        return $responseArray;
    }

    /**
     * @param $entity
     * @return array
     */
    public function getResultsByEntity($entity)
    {
        $results = $this->findBy(['entity' => $entity]);

        if (!$results) {
            throw new NotFoundException("This entity dont have a results ".$entity);
        }

        foreach ($results as $result){
            $responseArray [] = [
                "resultId" => $result->getId(),
                "resultEntity" => $result->getEntity()->getId(),
                "resultDate" => $result->getDate(),
                "resultPlace" => $result->getPlace(),
                "resultClient" => $result->getClient(),
                "resultUserId" => $result->getClient(),
                "resultValidated" => $result->getValidated(),
            ];
        }

        return $responseArray;
    }
}
