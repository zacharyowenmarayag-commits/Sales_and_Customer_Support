<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escalation extends Model
{
    protected $primaryKey = 'escalation_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'escalation_id', 'ticket_id', 'reason', 'priority',
        'assigned_manager', 'status', 'overdue_time', 'escalated_at',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'ticket_id');
    }
}
