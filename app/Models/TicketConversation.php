<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketConversation extends Model
{
    protected $primaryKey = 'conversation_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'conversation_id', 'ticket_id', 'sender_type',
        'sender_id', 'message', 'attachment', 'sent_at',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'ticket_id');
    }
}
