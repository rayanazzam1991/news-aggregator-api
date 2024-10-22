<?php

namespace Modules\UserPreferences\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\UserPreferences\Database\Factories\UserPreferenceFactory;

// use Modules\UserPreferences\Database\Factories\UserPreferenceFactory;

/**
 * @method static Builder<static>|UserPreference newModelQuery()
 * @method static Builder<static>|UserPreference newQuery()
 * @method static Builder<static>|UserPreference query()
 *
 * @mixin Eloquent
 */
class UserPreference extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected static function newFactory(): UserPreferenceFactory
    {
        return UserPreferenceFactory::new();
    }
}
