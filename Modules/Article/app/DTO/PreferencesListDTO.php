<?php

namespace Modules\Article\DTO;

use Spatie\LaravelData\Data;

class PreferencesListDTO extends Data
{
    public function __construct(
        public array $preferences
    ) {}

    /**
     * @param array{
     *     preference_id:int,
     *     preference_type:string,
     * } $request
     */
    public static function fromRequest(array $request) {}
}
