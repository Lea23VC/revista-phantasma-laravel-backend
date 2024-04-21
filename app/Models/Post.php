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


use App\Casts\SpanishDateCast;
use DOMDocument;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use Illuminate\Support\Str;
use Spatie\Image\Enums\CropPosition;

class Post extends Model implements HasMedia
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'publish_at',
        'author_id',
        'is_published',
    ];

    protected $casts = [
        'categories' => 'array',
        'attachments' => 'array',
        'is_published' => 'boolean',
    ];

    use HasFactory;
    use InteractsWithMedia;
    use HasSEO;

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function featuredImage(): ?Media
    {
        return $this->getMedia("default")?->first();
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->width(500)
            ->nonQueued();

        $this->addMediaConversion('featured')
            ->width(1920)
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

    public function getContentWithoutHTMLAttribute()
    {
        $cleanContent = strip_tags($this->content);
        $cleanContent = str_replace("\n", "", $cleanContent); // Remove newline characters
        return Str::limit($cleanContent, 150);
    }

    public function getContentImagesAttribute()
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($this->content); // Suppress warnings from invalid HTML
        $imageUrls = [];

        foreach ($dom->getElementsByTagName('img') as $img) {
            $url = $img->getAttribute('src');
            if (substr($url, -4) !== '.zip') { // Exclude if the URL ends with .zip
                $imageUrls[] = $url;
            }
        }

        return $imageUrls;
    }

    public function getSpanishDateAttribute()
    { // Check if the attribute exists in the model
        if (!isset($this->attributes['publish_at'])) {
            return null;
        }

        $value = $this->attributes['publish_at'];
        $spanishDateCast = new SpanishDateCast();

        // Use the 'get' method of the cast to format the date
        return $spanishDateCast->get($this, 'publish_at', $value, $this->attributes);
    }
}
