<?php

namespace App\Serializer\Decoder\Grade;

use App\Dto\Params\Grade\GradePostParams;
use App\Dto\Request\Grade\GradePostRequest;

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
