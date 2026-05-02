<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    protected $fillable = [
        'division_id',
        'district_id',
        'upazila_id',
        'union_id',
        'ward_id',
        'voter_id',
        'house_chief_name',
        'village_name',
        'holding_no',
        'mobile_no',
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

    public function voter()
    {
        return $this->belongsTo(Voter::class);
    }
}
