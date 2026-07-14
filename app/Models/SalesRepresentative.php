<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesRepresentative extends Model
{
    protected $primaryKey = 'rep_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'rep_id', 'first_name', 'last_name',
        'email', 'phone', 'region', 'sales_target', 'status',
    ];

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'rep_id', 'rep_id');
    }

    public function opportunities()
    {
        return $this->hasMany(Opportunity::class, 'rep_id', 'rep_id');
    }

    public function salesTargets()
    {
        return $this->hasMany(SalesTarget::class, 'rep_id', 'rep_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'rep_id', 'rep_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'rep_id', 'rep_id');
    }

    public function reportLogs()
    {
        return $this->hasMany(ReportLog::class, 'generated_by', 'rep_id');
    }
}
