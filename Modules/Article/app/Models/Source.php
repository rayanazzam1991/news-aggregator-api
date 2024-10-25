<?php

namespace Modules\Article\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Article\Database\Factories\SourceFactory;

/**
 * @property int $id
 * @property string $name
 * @property int|null $status
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static SourceFactory factory($count = null, $state = [])
 * @method static Builder<static>|Source newModelQuery()
 * @method static Builder<static>|Source newQuery()
 * @method static Builder<static>|Source onlyTrashed()
 * @method static Builder<static>|Source query()
 * @method static Builder<static>|Source whereCreatedAt($value)
 * @method static Builder<static>|Source whereDeletedAt($value)
 * @method static Builder<static>|Source whereId($value)
 * @method static Builder<static>|Source whereName($value)
 * @method static Builder<static>|Source whereStatus($value)
 * @method static Builder<static>|Source whereUpdatedAt($value)
 * @method static Builder<static>|Source withTrashed()
 * @method static Builder<static>|Source withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Source extends Model
{
    /** @use HasFactory<SourceFactory> */
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected static function newFactory(): SourceFactory
    {
        return SourceFactory::new();
    }
}
