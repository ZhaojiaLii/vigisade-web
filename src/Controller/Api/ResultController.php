<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Entity\Result;
use App\Entity\ResultQuestion;
use App\Entity\ResultTeamMember;
use App\Exception\Http\NotFoundException;
use App\Repository\AreaRepository;
use App\Repository\DirectionRepository;
use App\Repository\EntityRepository;
use App\Repository\ResultRepository;
use App\Repository\SurveyRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResultController extends ApiController
{
    private $em;
    private $surveyRepository;
    private $userRepository;
    private $directionRepository;
    private $areaRepository;
    private $entityRepository;
    private $resultRepository;

    /**
     * ResultController constructor.
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     * @param SurveyRepository $surveyRepository
     * @param DirectionRepository $directionRepository
     * @param AreaRepository $areaRepository
     * @param EntityRepository $entityRepository
     * @param ResultRepository $resultRepository
     */
    public function __construct (
        EntityManagerInterface $em,
        UserRepository $userRepository,
        SurveyRepository $surveyRepository,
        DirectionRepository $directionRepository,
        AreaRepository $areaRepository,
        EntityRepository $entityRepository,
        ResultRepository $resultRepository
    )
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->directionRepository = $directionRepository;
        $this->areaRepository = $areaRepository;
        $this->entityRepository = $entityRepository;
        $this->surveyRepository = $surveyRepository;
        $this->resultRepository = $resultRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function createResult(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if(empty($data)){
            throw new NotFoundException("please check the data");
        }

        $result = new Result();
        $result->setDate(new \DateTime());
        $result->setPlace($data['resultPlace']);
        $result->setClient($data['resultClient']);
        $result->setValidated($data['resultValidated']);
        $result->setBestPracticeDone($data['resultBestPracticeDone']);
        $result->setBestPracticeComment($data['resultBestPracticeComment']);
        $result->setBestPracticePhoto($data['resultBestPracticePhoto']);
        $result->setSurvey($this->surveyRepository->find($data['resultSurveyId']));
        $result->setUser($this->userRepository->find($data['resultUserId']));
        $result->setDirection($this->directionRepository->find($data['resultDirectionId']));
        $result->setArea($this->areaRepository->find($data['resultAreaId']));
        $result->setEntity($this->entityRepository->find($data['resultEntityId']));

        foreach ( $data['resultQuestion'] as $resultQuestionValue ){

            $resultQuestion = new ResultQuestion();
            $resultQuestion->setComment($resultQuestionValue['resultQuestionResultComment']);
            $resultQuestion->setNotation($resultQuestionValue['resultQuestionResultNotation']);
            $resultQuestion->setPhoto($resultQuestionValue['resultQuestionResultPhoto']);

            $result->addQuestion($resultQuestion);
        }

        foreach ( $data['resultTeamMember'] as $resultTeamMemberValue ){

            $resultTeamMember= new ResultTeamMember();
            $resultTeamMember->setFirstName($resultTeamMemberValue['resultTeamMemberFirstName']);
            $resultTeamMember->setLastName($resultTeamMemberValue['resultTeamMemberLastName']);
            $resultTeamMember->setRole($resultTeamMemberValue['resultTeamMemberRole']);

            $result->addTeamMember($resultTeamMember);
        }

        $this->em->persist($result);
        $this->em->flush();

        $responseArray = $this->resultRepository->getResultResponse($result->getId());

        return $this->createResponse('RESULT', $responseArray);
    }

    public function updateResult()
    {
        return new JsonResponse(null, 204);
    }

    public function getResults()
    {
        return new JsonResponse('survey results');
    }

    public function getResult(string $id)
    {
        return new JsonResponse(['survey result', $id]);
    }
}
