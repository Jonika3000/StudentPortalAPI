<?php

namespace App\Validator;

use App\Validator\Constraint\OnlyLetters;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class OnlyLettersValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint OnlyLetters */

        if (null === $value || '' === $value) {
            return;
        }

        if (!preg_match('/^\p{L}+$/u', (string) $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
