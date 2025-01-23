<?php

namespace App\Decoder\StudentSubmission;

use App\Params\StudentSubmission\StudentSubmissionPostParams;
use App\Request\StudentSubmission\StudentSubmissionPostRequest;

class StudentSubmissionPostDecoder
{
    public function decode(StudentSubmissionPostRequest $request): StudentSubmissionPostParams
    {
        return new StudentSubmissionPostParams(
            $request->comment,
            $request->homework,
            $request->submittedDate
        );
    }
}
