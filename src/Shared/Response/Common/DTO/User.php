<?php

namespace App\Shared\Response\Common\DTO;

class User
{
    public function __construct(
        public readonly int $id,
        public readonly string $uuid,
        public readonly string $firstName,
        public readonly string $secondName,
        public readonly string $email,
        public readonly ?string $avatarPath,
    ) {
    }
}