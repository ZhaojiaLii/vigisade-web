<?php

namespace App\Exception\Http;

interface HttpExceptionInterface
{
    /**
     * Gets a public error code which explain the error.
     */
    function getApiCode(): string;
}
