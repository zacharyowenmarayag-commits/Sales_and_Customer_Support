<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportCase extends Model
{
    protected $table = 'cases';
    protected $primaryKey = 'case_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'case_id',
        'customer_id',
        'issue',
        'priority',
        'status',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}
