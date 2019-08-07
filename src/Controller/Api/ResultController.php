<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Entity\CorrectiveAction;
use App\Entity\Result;
use App\Entity\ResultQuestion;
use App\Entity\ResultTeamMember;
use App\Repository\BestPracticeRepository;
use App\Service\UploadImageBase64;
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
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SurveyRepository
     */
    private $surveyRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

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

    /**
     * @var ResultRepository
     */
    private $resultRepository;

    /**
     * @var ResultQuestionRepository
     */
    private $resultQuestionRepository;

    /**
     * @var ResultTeamMemberRepository
     */
    private $resultTeamMemberRepository;

    /**
     * @var SurveyQuestionRepository
     */
    private $surveyQuestionRepository;

    /**
     * @var UploadImageBase64
     */
    private $uploadImageBase64;

    /**
     * @var BestPracticeRepository
     */
    private $bestPracticeRepository;

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
     * @param UploadImageBase64 $uploadImageBase64
     * @param BestPracticeRepository $bestPracticeRepository
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
        SurveyQuestionRepository $surveyQuestionRepository,
        UploadImageBase64 $uploadImageBase64,
        BestPracticeRepository $bestPracticeRepository
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
        $this->uploadImageBase64 = $uploadImageBase64;
        $this->bestPracticeRepository = $bestPracticeRepository;
    }

    /**
     * @param Request $request
     * @return array|\FOS\RestBundle\View\View|JsonResponse
     * @throws \Exception
     */
    public function createResult(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if(empty($data)){
            return ['message' => "The JSON sent contains invalid data or empty"];
        }

        // check all parameters mandatory of data
        $dataKeys =  ['resultSurveyId', 'resultUserId', 'resultDirectionId', 'resultAreaId', 'resultEntityId',
                      'resultClient', 'resultValidated', 'resultBestPracticeDone', 'resultBestPracticeComment',
                      'resultBestPracticePhoto'];

        foreach ($dataKeys as $key){
            if(!array_key_exists($key, $data)) {
                return new JsonResponse(['code' => '400', 'message' => "The parameter `".$key."` should be specified."], 400);
            }
        }

        // save Result
        $result = new Result();
        $result->setDate(new \DateTime($data['resultDate']));
        $result->setPlace($data['resultPlace']);
        $result->setClient($data['resultClient']);
        $result->setValidated($data['resultValidated']);
        $result->setBestPracticeType($this->bestPracticeRepository->find($data['resultBestPracticeTypeId']));
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
                $resultQuestion->setQuestion($this->SurveyQuestionRepository->find($resultQuestionValue['resultQuestionResultQuestionId']));

                // get picture with format Base64
                $imageBase64 = $resultQuestionValue['resultQuestionResultPhoto'];

                //get the path to store image result from service.yaml
                $path = $this->getParameter('app.path.result_images');

                //this service return the name of picture if uploaded true and return false if picture not uploaded
                $imageBase64 = $this->uploadImageBase64->UploadImage($imageBase64, __DIR__.'/../../../'.$path);

                if($imageBase64){
                    $resultQuestion->setPhoto($imageBase64);
                }

                $resultQuestion->setTeamMembers($this->resultTeamMemberRepository->find($memberTeamMapping[$resultQuestionValue['teamMemberId']]));
                $result->addQuestion($resultQuestion);
            } else {
                $resultQuestion = new ResultQuestion();
                $resultQuestion->setComment($resultQuestionValue['resultQuestionResultComment']);
                $resultQuestion->setNotation($resultQuestionValue['resultQuestionResultNotation']);
                $resultQuestion->setQuestion($this->SurveyQuestionRepository->find($resultQuestionValue['resultQuestionResultQuestionId']));

                // get picture with format Base64
                $imageBase64 = $resultQuestionValue['resultQuestionResultPhoto'];

                //get the path to store image result from service.yaml
                $path = $this->getParameter('app.path.result_images');

                //this service return the name of picture if uploaded true and return false if picture not uploaded
                $imageBase64 = $this->uploadImageBase64->UploadImage($imageBase64, __DIR__.'/../../../'.$path);

                if($imageBase64){
                    $resultQuestion->setPhoto($imageBase64);
                }

                $resultQuestion->setTeamMembers(null);
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
                $correctiveAction->setResultQuestion($resultQuestion);
                $correctiveAction->setDirection($result->getDirection());
                $correctiveAction->setArea($result->getArea());
                $correctiveAction->setEntity($result->getEntity());

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
     * this WS is not used
     * @param Request $request
     * @return \FOS\RestBundle\View\View|JsonResponse
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
     * @return \FOS\RestBundle\View\View
     */
    public function getResult(string $id)
    {
        $result = $this->resultRepository->getResultResponse($id);

        return $this->createResponse(SELF::RESULT, $result);
    }
}
