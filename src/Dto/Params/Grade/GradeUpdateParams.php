<?php

namespace App\Dto\Params\Grade;

class GradeUpdateParams
{
    public function __construct(
        public int $grade,
        public string $comment,
    ) {
    }
}
