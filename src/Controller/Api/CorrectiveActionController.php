<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CorrectiveActionController extends ApiController
{
    public function getCorrectiveActions()
    {
        return new JsonResponse(['corrective action form data']);
    }

    public function createResult()
    {
        return new JsonResponse(null, 204);
    }

    public function updateResult()
    {
        return new JsonResponse(null, 204);
    }
}
