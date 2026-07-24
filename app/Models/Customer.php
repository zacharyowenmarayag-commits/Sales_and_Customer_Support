<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'customer_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'customer_id', 'first_name', 'last_name',
        'email', 'phone', 'address', 'region_id',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'customer_id', 'customer_id');
    }

    public function opportunities()
    {
        return $this->hasMany(Opportunity::class, 'customer_id', 'customer_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'customer_id', 'customer_id');
    }

    public function feedback()
    {
        return $this->hasMany(CustomerFeedback::class, 'customer_id', 'customer_id');
    }
}
