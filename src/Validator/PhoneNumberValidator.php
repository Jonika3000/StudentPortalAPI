<?php

namespace App\Validator;

use App\Validator\Constraint\PhoneNumber;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PhoneNumberValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var PhoneNumber $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $pattern = '/^\+?[1-9]\d{1,14}$/';

        if (!preg_match($pattern, (string) $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
