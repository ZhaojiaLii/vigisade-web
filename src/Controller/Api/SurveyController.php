<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;

class SurveyController extends ApiController
{
    /**
     * Gets required data to build a visit form.
     */
    public function getSurvey()
    {
        return new JsonResponse('survey');
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
