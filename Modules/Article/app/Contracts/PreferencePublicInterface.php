<?php

namespace Modules\Article\Contracts;

interface PreferencePublicInterface
{
    public function validatePreference(int $id, string $type): bool;

    public function getPreference(int $id, string $type): string|null;

    public function getPreferenceTypeName(string $type): string;
}
