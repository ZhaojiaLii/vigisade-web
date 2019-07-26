<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Entity\CorrectiveAction;
use App\Entity\Result;
use App\Entity\ResultQuestion;
use App\Entity\ResultTeamMember;
use App\Repository\AreaRepository;
use App\Repository\DirectionRepository;
use App\Repository\EntityRepository;
use App\Repository\ResultQuestionRepository;
use App\Repository\ResultRepository;
use App\Repository\ResultTeamMemberRepository;
use App\Repository\SurveyRepository;
use App\Repository\SurveyQuestionRepository;
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
    private $resultQuestionRepository;
    private $resultTeamMemberRepository;
    private $surveyQuestionRepository;

    const RESULT = "RESULT";

    /**
     * ResultController constructor.
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     * @param SurveyRepository $surveyRepository
     * @param DirectionRepository $directionRepository
     * @param AreaRepository $areaRepository
     * @param EntityRepository $entityRepository
     * @param ResultRepository $resultRepository
     * @param ResultQuestionRepository $resultQuestionRepository
     * @param ResultTeamMemberRepository $resultTeamMemberRepository
     * @param SurveyQuestionRepository $surveyQuestionRepository
     */
    public function __construct (
        EntityManagerInterface $em,
        UserRepository $userRepository,
        SurveyRepository $surveyRepository,
        DirectionRepository $directionRepository,
        AreaRepository $areaRepository,
        EntityRepository $entityRepository,
        ResultRepository $resultRepository,
        ResultQuestionRepository $resultQuestionRepository,
        ResultTeamMemberRepository $resultTeamMemberRepository,
        SurveyQuestionRepository $surveyQuestionRepository
    )
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->directionRepository = $directionRepository;
        $this->areaRepository = $areaRepository;
        $this->entityRepository = $entityRepository;
        $this->surveyRepository = $surveyRepository;
        $this->resultRepository = $resultRepository;
        $this->resultQuestionRepository = $resultQuestionRepository;
        $this->resultTeamMemberRepository = $resultTeamMemberRepository;
        $this->SurveyQuestionRepository = $surveyQuestionRepository;
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
            return ['message' => "The JSON sent contains invalid data or empty"];
        }

        // check all parameters of data
        if(!array_key_exists('resultSurveyId', $data)) {
            return ['message' => "The parameter `resultSurveyId` should be specified."];
        }

        if(!array_key_exists('resultUserId', $data)) {
            return ['message' => "The parameter `resultUserId` should be specified."];
        }

        if(!array_key_exists('resultDirectionId', $data)) {
            return ['message' => "The parameter `resultDirectionId` should be specified."];
        }

        if(!array_key_exists('resultDirectionId', $data)) {
            return ['message' => "The parameter `resultDirectionId` should be specified."];
        }

        if(!array_key_exists('resultAreaId', $data)) {
            return ['message' => "The parameter `resultAreaId` should be specified."];
        }

        if(!array_key_exists('resultEntityId', $data)) {
            return ['message' => "The parameter `resultEntityId` should be specified."];
        }

        if(!array_key_exists('resultClient', $data)) {
            return ['message' => "The parameter `resultClient` should be specified."];
        }

        if(!array_key_exists('resultValidated', $data)) {
            return ['message' => "The parameter `resultValidated` should be specified."];
        }

        if(!array_key_exists('resultBestPracticeDone', $data)) {
            return ['message' => "The parameter `resultBestPracticeDone` should be specified."];
        }

        if(!array_key_exists('resultBestPracticeComment', $data)) {
            return ['message' => "The parameter `resultBestPracticeComment` should be specified."];
        }

        if(!array_key_exists('resultBestPracticePhoto', $data)) {
            return ['message' => "The parameter `resultBestPracticePhoto` should be specified."];
        }

        // save Result
        $result = new Result();
        $result->setDate(new \DateTime($data['resultDate']));
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

        $memberTeamMapping = []; // we stock id front in array
        foreach ( $data['resultTeamMember'] as $resultTeamMemberValue ){

            $resultTeamMember= new ResultTeamMember();
            $resultTeamMember->setFirstName($resultTeamMemberValue['resultTeamMemberFirstName']);
            $resultTeamMember->setLastName($resultTeamMemberValue['resultTeamMemberLastName']);
            $resultTeamMember->setRole($resultTeamMemberValue['resultTeamMemberRole']);

            $this->em->persist($resultTeamMember);
            $this->em->flush();

            // we affect the id_Doctrine to array with index id front exemple $memberTeamMapping['idFront'] = idDoctrine
            $memberTeamMapping[$resultTeamMemberValue['resultTeamMemberId']] = $resultTeamMember->getId();
        }

        foreach ( $data['resultQuestion'] as $resultQuestionValue ){

            if(!empty($memberTeamMapping[$resultQuestionValue['teamMemberId']] )){

                $resultQuestion = new ResultQuestion();
                $resultQuestion->setComment($resultQuestionValue['resultQuestionResultComment']);
                $resultQuestion->setNotation($resultQuestionValue['resultQuestionResultNotation']);
                $resultQuestion->setPhoto($resultQuestionValue['resultQuestionResultPhoto']);
                $resultQuestion->setQuestion($this->SurveyQuestionRepository->find($resultQuestionValue['resultQuestionResultQuestionId']));
                $resultQuestion->setPhoto($resultQuestionValue['resultQuestionResultPhoto']);
                $resultQuestion->setTeamMembers($this->resultTeamMemberRepository->find($memberTeamMapping[$resultQuestionValue['teamMemberId']]));

                $result->addQuestion($resultQuestion);
            }
        }
        $this->em->persist($result);
        $this->em->flush();

        // setResutl to member
        foreach ( $memberTeamMapping as $memberTeam) {
            $member = $this->resultTeamMemberRepository->find($memberTeam);
            $member->setResult($this->resultRepository->find($resultQuestion->getResult()->getId()));
        }

        // if notation = 4 we create corrective action
        $resultQuestions = $this->em
            ->getRepository(ResultQuestion::class)
            ->findBy(['result' => $result->getId()]);

        foreach ($resultQuestions as $resultQuestion){

            if($resultQuestion->getNotation() === 4){
                $correctiveAction = new CorrectiveAction();
                $correctiveAction->setUser($this->getUser());
                $correctiveAction->setStatus('A traiter');
                $correctiveAction->setQuestion($this->SurveyQuestionRepository->find($resultQuestion->getQuestion()->getId()));
                $correctiveAction->setResult($this->resultRepository->find($resultQuestion->getResult()->getId()));

                $this->em->persist($correctiveAction);

            }
            $this->em->flush();
        }

        if(!$this->em->contains($result)){

            return new JsonResponse(["message" => "NOT SAVED"], 400);
        }

        $responseArray = $this->resultRepository->getResultResponse($result->getId());

        return $this->createResponse(SELF::RESULT, $responseArray);
    }

    /**
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function updateResult(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if(empty($data)){
            $message = ['message' => "The JSON sent contains invalid data or empty"];

            return new JsonResponse($message, 400);
        }

        $result = $this->resultRepository->getResultByID($data['resultId']);

        $result->setDate(new \DateTime($data['resultDate']));
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
            $resultQuestion = $this->resultQuestionRepository->find($resultQuestionValue['resultQuestionId']);
            $resultQuestion->setComment($resultQuestionValue['resultQuestionResultComment']);
            $resultQuestion->setNotation($resultQuestionValue['resultQuestionResultNotation']);
            $resultQuestion->setPhoto($resultQuestionValue['resultQuestionResultPhoto']);
        }

        foreach ( $data['resultTeamMember'] as $resultTeamMemberValue ){
            $resultTeamMember = $this->resultTeamMemberRepository->find($resultTeamMemberValue['resultTeamMemberId']);
            $resultTeamMember->setFirstName($resultTeamMemberValue['resultTeamMemberFirstName']);
            $resultTeamMember->setLastName($resultTeamMemberValue['resultTeamMemberLastName']);
            $resultTeamMember->setRole($resultTeamMemberValue['resultTeamMemberRole']);
        }

        $this->em->persist($result);
        $this->em->flush();

        if(!$this->em->contains($result)){

            return new JsonResponse(["message" => "NOT UPDATED"], 400);
        }

        $responseArray = $this->resultRepository->getResultResponse($result->getId());

        return $this->createResponse(SELF::RESULT, $responseArray);
    }

    /**
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function getResults()
    {
        $userId = $this->getUser()->getId();

        $responseArray  = [
            "userId" => $userId,
            "result" => $this->resultRepository->getResultUserByRole($userId)
        ];

        return $this->createResponse(SELF::RESULT, $responseArray);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function getResult(string $id)
    {
        $result = $this->resultRepository->getResultResponse($id);

        return $this->createResponse(SELF::RESULT, $result);
    }
}
