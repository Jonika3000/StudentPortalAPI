<?php

namespace App\Decoder\Homework;

use App\Params\Homework\HomeworkUpdateParams;
use App\Request\Homework\HomeworkUpdateRequest;

class HomeworkUpdateDecoder
{
    public function decode(HomeworkUpdateRequest $request): HomeworkUpdateParams
    {
        return new HomeworkUpdateParams(
            $request->description,
            $request->deadline
        );
    }
}
