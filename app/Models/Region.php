<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $primaryKey = 'region_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['region_id', 'region_name'];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'region_id', 'region_id');
    }
}
