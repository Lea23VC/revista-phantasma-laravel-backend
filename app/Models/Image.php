<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'image_hash', 'width', 'height'];


    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
    public function media(): MorphOne
    {
        return $this->morphOne(Image::class, 'mediable');
    }
}
