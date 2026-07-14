<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegionSale extends Model
{
    protected $fillable = [
        'date_range',
        'region_name',
        'sales_amount',
        'vs_last_month'
    ];
}
