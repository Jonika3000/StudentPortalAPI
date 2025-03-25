<?php

namespace App\Serializer\Decoder\Homework;

use App\Dto\Params\Homework\HomeworkPostParams;
use App\Dto\Request\Homework\HomeworkPostRequest;

class HomeworkPostDecoder
{
    public function decode(HomeworkPostRequest $request): HomeworkPostParams
    {
        return new HomeworkPostParams(
            $request->description,
            $request->lesson,
            $request->deadline
        );
    }
}
