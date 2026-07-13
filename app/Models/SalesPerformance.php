<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesPerformance extends Model
{
    protected $fillable = [
        'date_range',
        'label',
        'sales_amount',
        'orders_count'
    ];
}
