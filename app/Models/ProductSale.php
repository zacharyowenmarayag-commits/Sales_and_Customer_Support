<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    protected $fillable = [
        'date_range',
        'product_name',
        'percentage',
        'color'
    ];
}
