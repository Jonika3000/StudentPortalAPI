<?php

namespace App\Decoder\Grade;

use App\Params\Grade\GradeUpdateParams;
use App\Shared\Request\Grade\GradeUpdateRequest;

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
