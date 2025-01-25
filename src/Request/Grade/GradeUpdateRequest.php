<?php

namespace App\Request\Grade;

use App\Shared\BaseRequest;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class GradeUpdateRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    public int $grade;

    #[Length(
        max: 255,
        maxMessage: 'Comment cannot be longer than {{ limit }} characters.'
    )]
    public string $comment;

}
