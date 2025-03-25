<?php

namespace App\Dto\Request\Grade;

use App\Shared\Request\BaseRequest;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class GradePostRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    public int $grade;

    #[Length(
        max: 255,
        maxMessage: 'Comment cannot be longer than {{ limit }} characters.'
    )]
    public string $comment;

    #[NotNull]
    public int $studentSubmission;
}
