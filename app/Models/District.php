<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
        'district_name',
        'division_id',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function upazilas()
    {
        return $this->hasMany(Upazila::class);
    }

    public function unions()
    {
        return $this->hasMany(Union::class);
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
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
    public function voters()
    {
        return $this->hasMany(Voter::class);
    }
}
