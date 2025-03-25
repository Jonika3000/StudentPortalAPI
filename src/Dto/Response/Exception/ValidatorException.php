<?php

namespace App\Shared\Response\Exception;

class ValidatorException extends \Exception
{
    public function __construct(private readonly array $violation)
    {
        parent::__construct();
    }

    public function getViolation(): array
    {
        return $this->violation;
    }
}
