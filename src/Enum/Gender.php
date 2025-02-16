<?php

namespace App\Enum;

enum Gender: string
{
    case Male = 'Male';
    case Female = 'Female';
    case NonBinary = 'Non-binary';

    public static function getLabel(): array
    {
        return [
            self::Male->value => 'Male',
            self::Female->value => 'Female',
            self::NonBinary->value => 'NonBinary',
        ];
    }
}
