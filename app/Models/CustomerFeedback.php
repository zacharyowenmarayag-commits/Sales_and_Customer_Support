<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerFeedback extends Model
{
    protected $primaryKey = 'feedback_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'feedback_id', 'ticket_id', 'customer_id',
        'rating', 'comment', 'submitted_at',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'ticket_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}
