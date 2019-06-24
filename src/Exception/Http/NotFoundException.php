<?php

namespace App\Exception\Http;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotFoundException extends NotFoundHttpException implements HttpExceptionInterface
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    function getApiCode(): string
    {
        return 'NOT_FOUND';
    }
}
