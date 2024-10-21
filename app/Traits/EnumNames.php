<?php

namespace App\Traits;

trait EnumNames
{
    /** Get an array of case names. */
    public static function names(): array
    {
        return array_column(static::cases(), 'name');
    }
}
