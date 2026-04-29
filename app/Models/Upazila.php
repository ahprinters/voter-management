<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upazila extends Model
{
    protected $fillable = [
        'upazila_name',
        'district_id',
        'division_id',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function unions()
    {
        return $this->hasMany(Union::class);
    }
    public function wards()
    {
        return $this->hasMany(Ward::class);
    }
    public function voters()
    {
        return $this->hasMany(Voter::class);
    }
}
