<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\BorderType;
use Spatie\Image\Enums\CropPosition;
use Spatie\Image\Enums\Fit;
use Spatie\Tags\HasTags;

class Author extends Model  implements HasMedia
{
    use InteractsWithMedia;

    use HasFactory;
    protected $fillable = ['name', 'url'];

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->crop(400, 400, CropPosition::Center)
            ->nonQueued();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
