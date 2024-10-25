<?php

namespace Modules\Article\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Modules\Article\Database\Factories\ArticleFactory;

/**
 * @property int $id
 * @property string $title
 * @property int|null $source_id
 * @property int|null $category_id
 * @property int|null $author_id
 * @property string|null $key_words
 * @property string|null $summary
 * @property string|null $image_url
 * @property string|null $news_url
 * @property string|null $meta
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Author|null $author
 * @property-read Category|null $category
 * @property-read Source|null $source
 *
 * @method static ArticleFactory factory($count = null, $state = [])
 * @method static Builder<static>|Article newModelQuery()
 * @method static Builder<static>|Article newQuery()
 * @method static Builder<static>|Article onlyTrashed()
 * @method static Builder<static>|Article query()
 * @method static Builder<static>|Article whereAuthorId($value)
 * @method static Builder<static>|Article whereCategoryId($value)
 * @method static Builder<static>|Article whereCreatedAt($value)
 * @method static Builder<static>|Article whereDeletedAt($value)
 * @method static Builder<static>|Article whereId($value)
 * @method static Builder<static>|Article whereImageUrl($value)
 * @method static Builder<static>|Article whereKeyWords($value)
 * @method static Builder<static>|Article whereMeta($value)
 * @method static Builder<static>|Article whereNewsUrl($value)
 * @method static Builder<static>|Article whereSourceId($value)
 * @method static Builder<static>|Article whereSummary($value)
 * @method static Builder<static>|Article whereTitle($value)
 * @method static Builder<static>|Article whereUpdatedAt($value)
 * @method static Builder<static>|Article withTrashed()
 * @method static Builder<static>|Article withoutTrashed()
 *
 * @mixin Eloquent
 */
class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected static function newFactory(): ArticleFactory
    {
        return ArticleFactory::new();
    }

    /**
     * @return BelongsTo<Author>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * @return BelongsTo<Category>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo<Source>
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::updating(function () {
            Cache::tags('articles')->flush(); // Use cache tags if necessary
        });

        static::created(function () {
            Cache::tags('articles')->flush(); // Use cache tags if necessary
        });
    }
}
