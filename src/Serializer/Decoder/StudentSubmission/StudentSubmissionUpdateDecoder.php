<?php

namespace App\Serializer\Decoder\StudentSubmission;

use App\Dto\Params\StudentSubmission\StudentSubmissionUpdateParams;
use App\Dto\Request\StudentSubmission\StudentSubmissionUpdateRequest;

class StudentSubmissionUpdateDecoder
{
    public function decode(StudentSubmissionUpdateRequest $request): StudentSubmissionUpdateParams
    {
        return new StudentSubmissionUpdateParams(
            $request->comment,
        );
    }
}
