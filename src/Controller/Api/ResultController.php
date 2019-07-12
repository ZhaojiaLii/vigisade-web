<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Entity\Result;
use App\Entity\ResultQuestion;
use App\Entity\ResultTeamMember;
use App\Entity\Survey;
use App\Exception\Http\NotFoundException;
use App\Repository\AreaRepository;
use App\Repository\DirectionRepository;
use App\Repository\EntityRepository;
use App\Repository\SurveyCategoryRepository;
use App\Repository\SurveyTranslationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\BestPracticeRepository;
use App\Repository\SurveyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResultController extends ApiController
{
    private $em;
    private $bestPracticeRepository;
    private $surveyRepository;
    private $surveyTranslationRepository;
    private $surveyCategoryRepository;
    private $userRepository;
    private $directionRepository;
    private $areaRepository;
    private $entityRepository;

    /**
     * ResultController constructor.
     * @param EntityManagerInterface $em
     * @param BestPracticeRepository $bestPracticeRepository
     * @param SurveyRepository $surveyRepository
     * @param SurveyTranslationRepository $surveyTranslationRepository
     * @param SurveyCategoryRepository $surveyCategoryRepository
     * @param UserRepository $userRepository
     * @param DirectionRepository $directionRepository
     * @param AreaRepository $areaRepository
     * @param EntityRepository $entityRepository
     */
    public function __construct (
        EntityManagerInterface $em,
        BestPracticeRepository $bestPracticeRepository,
        SurveyRepository $surveyRepository,
        SurveyTranslationRepository $surveyTranslationRepository,
        SurveyCategoryRepository $surveyCategoryRepository,
        UserRepository $userRepository,
        DirectionRepository $directionRepository,
        AreaRepository $areaRepository,
        EntityRepository $entityRepository
    )
    {
        $this->em = $em;
        $this->bestPracticeRepository = $bestPracticeRepository;
        $this->surveyRepository = $surveyRepository;
        $this->surveyTranslationRepository = $surveyTranslationRepository;
        $this->surveyCategoryRepository = $surveyCategoryRepository;
        $this->userRepository = $userRepository;
        $this->directionRepository = $directionRepository;
        $this->areaRepository = $areaRepository;
        $this->entityRepository = $entityRepository;
    }

    public function createResult(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if(empty($data)){
            throw new NotFoundException("data empty");
        }

        $result = new Result();
        $result->setDate(new \DateTime());
        $result->setPlace($data['result_place']);
        $result->setClient($data['result_client']);
        $result->setValidated($data['result_validated']);
        $result->setBestPracticeDone($data['result_best_practice_done']);
        $result->setBestPracticeComment($data['result_best_practice_comment']);
        $result->setBestPracticePhoto($data['result_best_practice_photo']);
        $result->setSurvey($this->surveyRepository->find($data['result_survey_id']));
        $result->setUser($this->userRepository->find($data['result_user_id']));
        $result->setDirection($this->directionRepository->find($data['result_direction_id']));
        $result->setArea($this->areaRepository->find($data['result_area_id']));
        $result->setEntity($this->entityRepository->find($data['result_entity_id']));

        foreach ( $data['result_question'] as $resultQuestionValue ){

            $resultQuestion = new ResultQuestion();
            $resultQuestion->setComment($resultQuestionValue['result_question_result_comment']);
            $resultQuestion->setNotation($resultQuestionValue['result_question_result_notation']);
            $resultQuestion->setPhoto($resultQuestionValue['result_question_result_photo']);

            $result->addQuestion($resultQuestion);
        }

        foreach ( $data['result_team_member'] as $resultTeamMemberValue ){

            $resultTeamMember= new ResultTeamMember();
            $resultTeamMember->setFirstName($resultTeamMemberValue['result_team_member_first_name']);
            $resultTeamMember->setLastName($resultTeamMemberValue['result_team_member_last_name']);
            $resultTeamMember->setRole($resultTeamMemberValue['result_team_member_role']);

            $result->addTeamMember($resultTeamMember);
        }

        $this->em->persist($result);
        $this->em->flush();


        $message = ['message' => 'the Result with ID `'.$result->getId().'` has been saved .'];

        return new JsonResponse($message, Response::HTTP_CREATED);
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
