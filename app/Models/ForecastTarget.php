<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForecastTarget extends Model
{
    protected $fillable = [
        'date_range',
        'category',
        'actual_amount',
        'target_amount'
    ];
}
