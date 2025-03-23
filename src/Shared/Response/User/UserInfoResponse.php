<?php

namespace App\Shared\Response\User;

class UserInfoResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $uuid,
        public readonly string $firstName,
        public readonly string $secondName,
        public readonly string $email,
        public readonly ?string $avatarPath,
        public readonly string $address,
        public readonly string $phoneNumber,
        public readonly string $gender,
        public readonly array $roles,
    ) {
    }
}
