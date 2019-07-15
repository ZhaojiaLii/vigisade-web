<?php

namespace App\Repository;

use App\Entity\Result;
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

    public function __construct(
        RegistryInterface $registry,
        EntityManagerInterface $em,
        ResultQuestionRepository $resultQuestionRepository,
        ResultTeamMemberRepository $resultTeamMemberRepository)
    {
        parent::__construct($registry, Result::class);
        $this->em = $em;
        $this->resultQuestionRepository = $resultQuestionRepository;
        $this->resultTeamMemberRepository = $resultTeamMemberRepository;
    }

    public function getResultByID($id)
    {
        $result = $this->em
            ->getRepository(Result::class)
            ->find($id);

        if (!$result) {
            throw new NotFoundException("This Result not exist ".$id);
        }
        return $result;
    }

    public function getResultResponse($id)
    {
        $result = $this->em
            ->getRepository(Result::class)
            ->find($id);

        if (!$result) {
            throw new NotFoundException("This Result not exist ".$id);
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
}
