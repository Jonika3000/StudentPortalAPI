<?php

namespace App\Dto\Params\User;

class UserEditParams
{
    public function __construct(
        public string $address,
        public string $phoneNumber,
    ) {
    }
}
