<?php

namespace App\Services;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class ValidatorService
{
    public function __construct(
        private ValidatorInterface $validator
    )
    {
    }

    public function validateObject(mixed $object): bool
    {
        $errors = $this->validator->validate($object);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            throw new BadRequestHttpException(implode(', ', $errorMessages));
        }

        return true;
    }
}
