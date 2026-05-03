<?php

namespace App\Models;

use App\Models\VoterComment;
use Illuminate\Database\Eloquent\Model;

class Voter extends Model
{
    protected $fillable = [
        'name',
        'father_name',
        'mother_name',
        'voter_number',
        'current_location',
        'division_id',
        'district_id',
        'upazila_id',
        'union_id',
        'ward_id',
        'village_id',
        'house_id',
    ];



    public function comments()
    {
        return $this->hasMany(VoterComment::class);
    }
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
}
