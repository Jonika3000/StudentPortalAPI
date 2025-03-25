<?php

namespace App\Serializer\Decoder\Homework;

use App\Dto\Params\Homework\HomeworkUpdateParams;
use App\Dto\Request\Homework\HomeworkUpdateRequest;

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
