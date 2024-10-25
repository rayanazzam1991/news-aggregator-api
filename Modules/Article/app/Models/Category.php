<?php

namespace Modules\Article\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Article\Database\Factories\CategoryFactory;

/**
 * @property int $id
 * @property string $name
 * @property int|null $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static CategoryFactory factory($count = null, $state = [])
 * @method static Builder<static>|Category newModelQuery()
 * @method static Builder<static>|Category newQuery()
 * @method static Builder<static>|Category onlyTrashed()
 * @method static Builder<static>|Category query()
 * @method static Builder<static>|Category whereCreatedAt($value)
 * @method static Builder<static>|Category whereId($value)
 * @method static Builder<static>|Category whereName($value)
 * @method static Builder<static>|Category whereStatus($value)
 * @method static Builder<static>|Category whereUpdatedAt($value)
 * @method static Builder<static>|Category withTrashed()
 * @method static Builder<static>|Category withoutTrashed()
 *
 * @mixin Eloquent
 */
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }
}
