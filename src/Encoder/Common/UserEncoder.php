<?php

namespace App\Encoder\Common;

use App\Entity\User;
use App\Shared\Response\Common\DTO\User as UserDTO;

class UserEncoder
{
    public function encode(User $user): UserDTO
    {
        return new UserDTO(
            id: $user->getId(),
            uuid: $user->getUuid(),
            firstName: $user->getFirstName(),
            secondName: $user->getSecondName(),
            email: $user->getEmail(),
            avatarPath: $user->getAvatarPath()
        );
    }
}
