<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportLog extends Model
{
    protected $primaryKey = 'report_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'report_id', 'generated_by', 'report_type',
        'date_from', 'date_to', 'generated_at', 'file_format',
    ];

    public function generatedBy()
    {
        return $this->belongsTo(SalesRepresentative::class, 'generated_by', 'rep_id');
    }
}
