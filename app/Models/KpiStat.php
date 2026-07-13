<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiStat extends Model
{
    protected $fillable = [
        'date_range',
        'total_sales',
        'sales_delta',
        'total_orders',
        'orders_delta',
        'avg_deal_size',
        'deal_delta',
        'win_rate',
        'win_delta'
    ];
}
