<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $primaryKey = 'order_item_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'order_item_id', 'order_id', 'product_id',
        'quantity', 'unit_price', 'line_total',
    ];

    public function order()
    {
        return $this->belongsTo(SalesOrder::class, 'order_id', 'order_id');
    }
}
