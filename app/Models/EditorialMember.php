<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Image\Enums\CropPosition;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use Spatie\Tags\HasTags;

class EditorialMember extends Model implements HasMedia, Sortable
{
    use InteractsWithMedia;

    use SortableTrait;
    use HasFactory;
    protected $fillable = ['name', 'email', 'position', 'author_id', 'receive_emails'];

    protected $casts = [
        'receive_emails' => 'boolean',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->crop(400, 400, CropPosition::Center)
            ->nonQueued();
    }
}
