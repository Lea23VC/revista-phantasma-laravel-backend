<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Carbon;

class SpanishDateCast implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        // Format the date in Spanish
        return Carbon::parse($value)->isoFormat('D MMM YYYY', 'Do MMM YYYY');
    }

    public function set($model, $key, $value, $attributes)
    {
        // Parse the incoming value if needed
        return $value;
    }
}
