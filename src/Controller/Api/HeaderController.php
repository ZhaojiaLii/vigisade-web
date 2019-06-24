<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;

class HeaderController extends ApiController
{
    public function getSettings()
    {
        return new JsonResponse('settings');
    }
}
