<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;
use Spatie\Tags\HasTags;

class Post extends Model  implements HasMedia
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'publish_at'
    ];

    protected $casts = [
        'categories' => 'array',
        'attachments' => 'array',
    ];

    use HasTags;
    use HasFactory;
    use InteractsWithMedia;

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
    public function featuredImage()
    {
        return $this->getMedia("default")->first();
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }
}
