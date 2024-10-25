<?php

use Illuminate\Contracts\Config\Repository;
use Tests\RefreshDatabaseWithSeed;
use Illuminate\Support\Facades\Config;

pest()
    ->use(RefreshDatabaseWithSeed::class)
    ->in(
        './Feature',
        '../Modules/*/tests/Feature',
        '../Modules/*/tests/Unit',
    )->beforeEach(function () {
        Http::preventStrayRequests();
    });
