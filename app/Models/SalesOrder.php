<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $primaryKey = 'order_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'order_id', 'customer_id', 'rep_id', 'order_date',
        'status', 'subtotal', 'tax_amount', 'discount_amount',
        'total_amount', 'branch', 'payment_terms',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function rep()
    {
        return $this->belongsTo(SalesRepresentative::class, 'rep_id', 'rep_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id', 'order_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'order_id', 'order_id');
    }
}
