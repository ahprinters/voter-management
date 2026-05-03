<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $fillable = [
        'division_name',
    ];

    public function districts()
    {
        return $this->hasMany(District::class);
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
    public function voters()
    {
        return $this->hasMany(Voter::class);
    }
}
