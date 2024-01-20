<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Carbon;

class SpanishDateCast implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return Carbon::parse($value)->isoFormat('D MMM YYYY', 'Do MMM YYYY');
    }

    public function set($model, $key, $value, $attributes)
    {
        return $value;
    }
}
