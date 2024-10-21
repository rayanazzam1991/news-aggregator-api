<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class RefreshDatabaseWithSeed extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;
}
