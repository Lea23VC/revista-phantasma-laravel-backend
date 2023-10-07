<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Author extends Model
{

    use HasFactory;
    protected $fillable = ['name', 'url'];

    public function profilePic(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
