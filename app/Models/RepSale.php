<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepSale extends Model
{
    protected $fillable = [
        'date_range',
        'rep_name',
        'sales_amount',
        'vs_target'
    ];
}
