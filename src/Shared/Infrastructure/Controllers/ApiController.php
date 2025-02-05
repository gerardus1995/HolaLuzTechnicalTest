<?php

namespace App\Shared\Infrastructure\Controllers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class ApiController extends AbstractController
{
    public function __construct() {}

    public function validate(array $request, Assert\Collection $constraint): void
    {
        $validationErrors =  Validation::createValidator()->validate($request, $constraint);

        if ($validationErrors->count() == 0) {
            return;
        }

        $errors_array = [];

        foreach ($validationErrors as $violation) {
            $errors_array[] = $violation->getPropertyPath() . " : " . $violation->getMessage();
        }
        throw new HttpException(400, json_encode($errors_array));
    }
}
