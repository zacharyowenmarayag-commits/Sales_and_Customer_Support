<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTarget extends Model
{
    protected $primaryKey = 'target_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'target_id', 'rep_id', 'month', 'target_amount', 'actual_amount',
    ];

    public function rep()
    {
        return $this->belongsTo(SalesRepresentative::class, 'rep_id', 'rep_id');
    }
}
