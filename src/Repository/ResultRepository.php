<?php

namespace App\Repository;

use App\Entity\Result;
use App\Exception\Http\NotFoundException;
use App\Repository\ResultQuestionRepository;
use App\Repository\ResultTeamMemberRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

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

        /*if (!$result) {
            return ['message' => "This Result with id ".$id." not exist "];
        }*/
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

        if ($result) {

            $responseArray = [
                "resultId" => $result->getId(),
                "resultSurveyId" => $result->getSurvey()->getId(),
                "resultUserId" => $result->getUser()->getId(),
                "resultDirectionId" => $result->getDirection()->getId(),
                "resultAreaId" => $result->getArea()->getId(),
                "resultEntityId" => $result->getEntity()->getId(),
                "resultDate" => date_format($result->getDate() , 'Y-m-d'),
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

        return ['message' => "This Result with id ".$id." not exist"];
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

        if ($results) {
            $responseArray = [];
            if($roleUser[0] === "ROLE_CONDUCTEUR"){

                foreach ($results as $result){

                    $responseArray [] = [
                        "resultId" => $result->getId(),
                        "resultDirection" => $result->getDirection()->getId(),
                        "resultArea" => $result->getArea()->getId(),
                        "resultEntity" => $result->getEntity()->getId(),
                        "resultDate" => $result->getDate(),
                        "resultPlace" => $result->getPlace(),
                        "resultClient" => $result->getClient(),
                        "resultUserId" => $result->getUser()->getId(),
                        "resultValidated" => $result->getValidated(),
                    ];
                }

                return $responseArray;

            }else if($roleUser[0] === "ROLE_MANAGER"){

                return $this->getResultsByEntity($user->getEntity());

            }else if($roleUser[0] === "ROLE_ADMIN"){

                return $this->getResultsByDirection($user->getDirection());
            }
        }
    }

    /**
     * @param $direction
     * @return array
     */
    public function getResultsByDirection($direction)
    {
        $results = $this->findBy(['direction' => $direction]);

        if ($results) {
            $responseArray = [];
            foreach ($results as $result) {
                $responseArray [] = [
                    "resultId" => $result->getId(),
                    "resultDirection" => $result->getDirection()->getId(),
                    "resultArea" => $result->getArea()->getId(),
                    "resultEntity" => $result->getEntity()->getId(),
                    "resultDate" => $result->getDate(),
                    "resultPlace" => $result->getPlace(),
                    "resultClient" => $result->getClient(),
                    "resultUserId" => $result->getUser()->getId(),
                    "resultValidated" => $result->getValidated(),
                ];
            }

            return $responseArray;
        }

        //return ['message' => "The direction with name `".$direction."` dont have a results"];
    }

    /**
     * @param $entity
     * @return array
     */
    public function getResultsByEntity($entity)
    {
        $results = $this->findBy(['entity' => $entity]);

        if ($results) {
            $responseArray = [];
            foreach ($results as $result) {
                $responseArray [] = [
                    "resultId" => $result->getId(),
                    "resultDirection" => $result->getDirection()->getId(),
                    "resultArea" => $result->getArea()->getId(),
                    "resultEntity" => $result->getEntity()->getId(),
                    "resultDate" => $result->getDate(),
                    "resultPlace" => $result->getPlace(),
                    "resultClient" => $result->getClient(),
                    "resultUserId" => $result->getUser()->getId(),
                    "resultValidated" => $result->getValidated(),
                ];
            }

            return $responseArray;
        }

        //return ['message' => "This entity with `".$entity."`` dont have a results "];
    }
}
