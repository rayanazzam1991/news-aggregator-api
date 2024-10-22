<?php

namespace Modules\Article\Contracts;

interface PreferencePublicInterface
{

    public function validatePreference(int $id, string $type);

    public function getPreference(int $id, string $type);
    public function getPreferenceTypeName( string $type);

}
