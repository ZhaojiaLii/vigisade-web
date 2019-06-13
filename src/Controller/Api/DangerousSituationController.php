<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;

class DangerousSituationController extends ApiController
{
    public function create()
    {
        return new JsonResponse(null, 204);
    }
}
