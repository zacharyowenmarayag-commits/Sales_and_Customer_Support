<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlaMonitoring extends Model
{
    protected $table = 'sla_monitoring';
    protected $primaryKey = 'monitoring_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'monitoring_id', 'ticket_id', 'sla_rule_id',
        'response_due', 'resolution_due', 'first_response_time',
        'resolution_time', 'sla_status', 'compliance_percentage', 'last_checked',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'ticket_id');
    }

    public function slaRule()
    {
        return $this->belongsTo(SlaRule::class, 'sla_rule_id', 'sla_rule_id');
    }
}
