<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\CropPosition;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Page extends Model implements HasMedia
{
    use HasFactory;
    protected $with = ['media'];
    protected $fillable = ['title', 'slug', 'content'];

    use InteractsWithMedia;


    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->crop(400, 400, CropPosition::Center)
            ->nonQueued();
    }
    public function backgroundImage()
    {
        return $this->getMedia("default")->first();
    }
}
