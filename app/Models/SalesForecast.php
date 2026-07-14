<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesForecast extends Model
{
    protected $primaryKey = 'forecast_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'forecast_id', 'forecast_period', 'forecast_amount', 'generated_date',
    ];
}
