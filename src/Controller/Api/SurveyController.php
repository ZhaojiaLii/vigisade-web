<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Entity\Survey;
use App\Repository\SurveyCategoryRepository;
use App\Repository\SurveyTranslationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\BestPracticeRepository;
use App\Repository\SurveyRepository;

class SurveyController extends ApiController
{
    private $em;
    private $bestPracticeRepository;
    private $surveyRepository;
    private $surveyTranslationRepository;
    private $surveyCategoryRepository;

    /**
     * SurveyController constructor.
     * @param EntityManagerInterface $em
     * @param BestPracticeRepository $bestPracticeRepository
     * @param SurveyRepository $surveyRepository
     * @param SurveyTranslationRepository $surveyTranslationRepository
     * @param SurveyCategoryRepository $surveyCategoryRepository
     */
    public function __construct (
        EntityManagerInterface $em,
        BestPracticeRepository $bestPracticeRepository,
        SurveyRepository $surveyRepository,
        SurveyTranslationRepository $surveyTranslationRepository,
        SurveyCategoryRepository $surveyCategoryRepository
    )
    {
        $this->em = $em;
        $this->bestPracticeRepository = $bestPracticeRepository;
        $this->surveyRepository = $surveyRepository;
        $this->surveyTranslationRepository = $surveyTranslationRepository;
        $this->surveyCategoryRepository = $surveyCategoryRepository;
    }

    /**
     * @return \FOS\RestBundle\View\View
     */
    public function getSurvey()
    {
        $direction = $this->getUser()->getDirection();
        $userLanguage = $this->getUser()->getLanguage();

        if(empty($userLanguage)){ $userLanguage = 'fr'; } // language by default

        if (!$direction) {
            $message = [
                'code' => '400',
                'message' => 'The user is not related to any direction'];

            return new JsonResponse($message, 400);
        }


        $survey = $this->em
            ->getRepository(Survey::class)
            ->findOneBy(['direction' => $direction]);

        if (!$survey) {
            $message = [
                'code' => '204',
                'message' => 'The direction of this user is not related to any questionnaire'];

            return new JsonResponse($message, 204);
        }

        $responseArray = [
            "surveyId" => $survey->getId(),
            "surveyDirectionId" => $survey->getDirection() ? $survey->getDirection()->getId() : null,
            "surveyAreaId" => $this->getUser()->getArea()? $this->getUser()->getArea()->getId() : null,
            "surveyEntityId" => $this->getUser()->getEntity() ? $this->getUser()->getEntity()->getId() : null,
            "surveyTeam" => $survey->getTeam() ? $survey->getTeam() : null,
            "typeBestPractice" => $this->bestPracticeRepository->getAllTypeBestPractice($userLanguage),
            "bestPracticeTranslation" =>
                $this->surveyTranslationRepository->getBestPracticeTranslation($survey->getId(), $userLanguage),
            "surveyCategories" => $this->surveyCategoryRepository->getSurveyCategory($survey->getId(), $userLanguage),
        ];

        return $this->createResponse('SURVEY', $responseArray);
    }
}
