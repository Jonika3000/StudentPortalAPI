<?php

namespace App\Validator;

use App\Shared\Response\ConstraintViolation;
use App\Shared\Response\Exception\ValidatorException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FileValidator
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    /**
     * @throws ValidatorException
     */
    public function validateFiles(array $files): void
    {
        $violations = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $constraint = new Assert\File([
                    'maxSize' => '5M',
                    'mimeTypes' => ['image/jpeg', 'image/png', 'application/pdf'],
                    'mimeTypesMessage' => 'Only JPEG, PNG, and PDF files are allowed.',
                ]);

                $errors = $this->validator->validate($file, $constraint);
                foreach ($errors as $error) {
                    $violations[] = new ConstraintViolation(
                        'files',
                        $file->getClientOriginalName(),
                        $error->getMessage()
                    );
                }
            }
        }

        if (!empty($violations)) {
            throw new ValidatorException($violations);
        }
    }
}
