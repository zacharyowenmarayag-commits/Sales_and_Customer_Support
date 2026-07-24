<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['communication_log_id', 'task', 'customer', 'due_date', 'status'])]
class FollowUp extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    public const STATUSES = [
        'Pending',
        'In Progress',
        'Completed',
        'Cancelled',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    public function communicationLog()
    {
        return $this->belongsTo(CommunicationLog::class);
    }
}
