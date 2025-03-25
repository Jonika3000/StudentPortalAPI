<?php

namespace App\Support\Utils;

class Converter
{
    public static function convertToDateTime(string $date): \DateTime
    {
        $result = \DateTime::createFromFormat('Y-m-d', $date);
        if (!$result) {
            throw new \InvalidArgumentException('Invalid string format. Use YYYY-MM-DD.');
        }

        return $result;
    }
}
