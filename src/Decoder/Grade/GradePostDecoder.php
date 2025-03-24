<?php

namespace App\Decoder\Grade;

use App\Params\Grade\GradePostParams;
use App\Shared\Request\Grade\GradePostRequest;

class GradePostDecoder
{
    public function decode(GradePostRequest $request): GradePostParams
    {
        return new GradePostParams(
            $request->grade,
            $request->comment,
            $request->studentSubmission,
        );
    }
}
