<?php

namespace App\Decoder\StudentSubmission;

use App\Params\StudentSubmission\StudentSubmissionUpdateParams;
use App\Request\StudentSubmission\StudentSubmissionUpdateRequest;

class StudentSubmissionUpdateDecoder
{
    public function decode(StudentSubmissionUpdateRequest $request): StudentSubmissionUpdateParams
    {
        return new StudentSubmissionUpdateParams(
            $request->comment,
        );
    }
}
