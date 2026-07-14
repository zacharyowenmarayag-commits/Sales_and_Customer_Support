<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlaRule extends Model
{
    protected $primaryKey = 'sla_rule_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'sla_rule_id', 'priority', 'response_time_goal',
        'resolution_time_goal', 'escalation_time', 'status', 'description',
    ];

    public function monitoring()
    {
        return $this->hasMany(SlaMonitoring::class, 'sla_rule_id', 'sla_rule_id');
    }
}
