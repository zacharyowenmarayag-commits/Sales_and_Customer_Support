<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerSegment extends Model
{
    protected $primaryKey = 'segment_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['segment_id', 'segment_name', 'description'];
}
