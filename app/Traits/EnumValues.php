<?php

declare(strict_types=1);

namespace App\Traits;

trait EnumValues
{
    /** Get an array of case values. */
    /**
     * @return array<mixed>
     */
    public static function values(): array
    {
        $cases = static::cases();

        return array_column($cases, 'value');
    }
}
