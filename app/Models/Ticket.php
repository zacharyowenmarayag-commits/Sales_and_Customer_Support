<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $primaryKey = 'ticket_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ticket_id', 'customer_id', 'order_id', 'rep_id',
        'subject', 'description', 'category', 'priority', 'status',
        'created_at_ts', 'updated_at_ts', 'closed_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function order()
    {
        return $this->belongsTo(SalesOrder::class, 'order_id', 'order_id');
    }

    public function rep()
    {
        return $this->belongsTo(SalesRepresentative::class, 'rep_id', 'rep_id');
    }

    public function conversations()
    {
        return $this->hasMany(TicketConversation::class, 'ticket_id', 'ticket_id');
    }

    public function slaMonitoring()
    {
        return $this->hasMany(SlaMonitoring::class, 'ticket_id', 'ticket_id');
    }

    public function escalations()
    {
        return $this->hasMany(Escalation::class, 'ticket_id', 'ticket_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'ticket_id', 'ticket_id');
    }

    public function feedback()
    {
        return $this->hasMany(CustomerFeedback::class, 'ticket_id', 'ticket_id');
    }
}
