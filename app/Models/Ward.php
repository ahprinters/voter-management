<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $fillable = [
        'ward_name',
        'ward_number',
        'union_id',
        'upazila_id',
        'district_id',
        'division_id',
    ];

    public function union()
    {
        return $this->belongsTo(Union::class);
    }
    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }
    public function district()
    {
        return $this->belongsTo(District::class);
    }
    public function division()
    {
        return $this->belongsTo(Division::class);
    }
    public function voters()
    {
        return $this->hasMany(Voter::class);
    }
    public function houses()
    {
        return $this->hasMany(House::class);
    }
}
