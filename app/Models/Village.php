<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $fillable = [
        'village_name',
        'ward_id',
        'union_id',
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
    public function union()
    {
        return $this->belongsTo(Union::class);
    }
    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }
    public function houses()
    {
        return $this->hasMany(House::class);
    }
    public function voters()
    {
        return $this->hasMany(Voter::class);
    }
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
