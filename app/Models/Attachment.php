<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use Spatie\Tags\HasTags;

class Attachment extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $with = ['media'];

    protected $fillable = ['title', 'description', 'post_id', 'position'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
    public function files()
    {
        return $this->getMedia("files");
    }
}
