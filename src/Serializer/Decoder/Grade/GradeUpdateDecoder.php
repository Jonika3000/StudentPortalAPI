<?php

namespace App\Serializer\Decoder\Grade;

use App\Dto\Params\Grade\GradeUpdateParams;
use App\Dto\Request\Grade\GradeUpdateRequest;

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
