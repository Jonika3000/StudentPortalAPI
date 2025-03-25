<?php

namespace App\Shared\Response;

readonly class ConstraintViolation
{
    public function __construct(
        public string $property,
        public string $value,
        public string $message,
    ) {
    }
}
