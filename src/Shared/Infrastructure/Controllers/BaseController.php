<?php

namespace App\Shared\Infrastructure\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends ApiController
{
    public function __invoke(): Response
    {
        return new JsonResponse(['success' => true]);
    }
}
