<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Entity\Survey;
use App\Exception\Http\NotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class SurveyController extends ApiController
{
    /**
     * Gets required data to build a visit form.
     */
    public function getSurvey(EntityManagerInterface $em)
    {
        $direction = $this->getUser()->getDirection();

        if (!$direction) {
            // @todo: translate
            throw new NotFoundException("L'utilisateur n'est pas relié à une direction.");
        }

        $survey = $em
            ->getRepository(Survey::class)
            ->findOneBy(['direction' => $direction]);

        if (!$survey) {
            // @todo: translate
            throw new NotFoundException("La direction de l'utilisateur n'est reliée à aucun questionnaire.");
        }

        return $this->createResponse('SURVEY', $survey);
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
