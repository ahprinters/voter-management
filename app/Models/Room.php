<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'house_id',
        'holding_no',
        'room_name',
        'division_id',
        'district_id',
        'upazila_id',
        'union_id',
        'ward_id',
        'village_id',
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

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function house()
    {
        return $this->belongsTo(House::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
