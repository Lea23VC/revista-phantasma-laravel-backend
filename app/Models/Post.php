<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
    ];
    use HasFactory;

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
    public function featuredImage(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
