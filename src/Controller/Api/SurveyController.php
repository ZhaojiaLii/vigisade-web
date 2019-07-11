<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Entity\Survey;
use App\Exception\Http\NotFoundException;
use App\Repository\SurveyCategoryRepository;
use App\Repository\SurveyTranslationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\BestPracticeRepository;
use App\Repository\SurveyRepository;

class SurveyController extends ApiController
{
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
    public function __construct(EntityManagerInterface $em,
                                BestPracticeRepository $bestPracticeRepository,
                                SurveyRepository $surveyRepository,
                                SurveyTranslationRepository $surveyTranslationRepository,
                                SurveyCategoryRepository $surveyCategoryRepository)
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

        if (!$direction) {
            throw new NotFoundException("L'utilisateur n'est pas relié à une direction.");
        }

        $survey = $this->em
            ->getRepository(Survey::class)
            ->findOneBy(['direction' => $direction]);

        if (!$survey) {
            throw new NotFoundException("La direction de l'utilisateur n'est reliée à aucun questionnaire.");
        }

        $responseArray[] = [
            "survey_id" => $survey->getId(),
            "survey_direction_id" => $survey->getDirection()->getId(),
            "survey_team" => $survey->getTeam(),
            "Type_best_practice" => $this->bestPracticeRepository->getAllTypeBestPractice(),
            "best_practice_translation" => $this->surveyTranslationRepository->getBestPracticeTranslation($survey->getId()),
            "survey_categories" => $this->surveyCategoryRepository->getSurveyCategory($survey->getId()),
        ];

        return $this->createResponse('SURVEY', $responseArray[0]);
    }

    public function createResult()
    {
        return new JsonResponse(null, 204);
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
