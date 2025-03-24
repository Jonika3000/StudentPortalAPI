<?php

namespace App\Shared\Request\StudentSubmission;

use App\Shared\Request\BaseRequest;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class StudentSubmissionUpdateRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[Length(
        max: 255,
        maxMessage: 'Comment cannot be longer than {{ limit }} characters.'
    )]
    public string $comment;
}
