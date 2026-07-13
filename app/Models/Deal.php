<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $fillable = [
        'date_range',
        'name',
        'customer',
        'stage',
        'value',
        'expected_close',
        'owner',
        'is_ongoing'
    ];
}
