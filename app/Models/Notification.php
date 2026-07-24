<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $primaryKey = 'notification_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'notification_id', 'rep_id', 'ticket_id',
        'title', 'message', 'type', 'is_read', 'notified_at',
    ];

    public function rep()
    {
        return $this->belongsTo(SalesRepresentative::class, 'rep_id', 'rep_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'ticket_id');
    }
}
