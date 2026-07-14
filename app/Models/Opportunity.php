<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    protected $primaryKey = 'deal_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'deal_id', 'customer_id', 'rep_id',
        'stage', 'expected_close_date', 'deal_value',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function rep()
    {
        return $this->belongsTo(SalesRepresentative::class, 'rep_id', 'rep_id');
    }
}
