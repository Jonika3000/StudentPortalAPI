<?php

namespace App\Serializer\Decoder\StudentSubmission;

use App\Dto\Params\StudentSubmission\StudentSubmissionPostParams;
use App\Dto\Request\StudentSubmission\StudentSubmissionPostRequest;

class StudentSubmissionPostDecoder
{
    public function decode(StudentSubmissionPostRequest $request): StudentSubmissionPostParams
    {
        return new StudentSubmissionPostParams(
            $request->comment,
            $request->homework
        );
    }
}
