<?php

namespace App\Decoder\Grade;

use App\Params\Grade\GradeUpdateParams;
use App\Request\Grade\GradeUpdateRequest;

class GradeUpdateDecoder
{
    public function decode(GradeUpdateRequest $request): GradeUpdateParams
    {
        return new GradeUpdateParams(
            $request->grade,
            $request->comment
        );
    }
}
