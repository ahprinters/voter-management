<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Union extends Model
{
    protected $fillable = [
        'union_name',
        'upazila_id',
        'district_id',
        'division_id',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }

    public function wards()
    {
        return $this->hasMany(Ward::class);
    }
    public function villages()
    {
        return $this->hasMany(Village::class);
    }
        public function houses()
    {
        return $this->hasMany(House::class);
    }
    public function voters()
    {
        return $this->hasMany(Voter::class);
    }
}
