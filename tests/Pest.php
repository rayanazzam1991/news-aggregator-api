<?php

use Tests\RefreshDatabaseWithSeed;

pest()
    ->use(RefreshDatabaseWithSeed::class)
    ->in(
        './Feature',
        '../Modules/*/tests/Feature',
        '../Modules/*/tests/Unit',
    )->beforeEach(function () {
        Http::preventStrayRequests();
    });
