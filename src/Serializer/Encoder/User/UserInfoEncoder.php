<?php

namespace App\Serializer\Encoder\User;

use App\Entity\User;
use App\Enum\Gender;
use App\Shared\Response\User\UserInfoResponse;

class UserInfoEncoder
{
    public function encode(User $user): UserInfoResponse
    {
        return new UserInfoResponse(
            id: $user->getId(),
            uuid: $user->getUuid(),
            firstName: $user->getFirstName(),
            secondName: $user->getSecondName(),
            email: $user->getEmail(),
            avatarPath: $user->getAvatarPath(),
            address: $user->getAddress(),
            phoneNumber: $user->getPhoneNumber(),
            gender: $user->getGender() instanceof Gender ? $user->getGender()->value : null,
            roles: $user->getRoles()
        );
    }
}
