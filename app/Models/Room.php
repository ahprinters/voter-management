<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'house_id',
        'holding_no',
        'room_name',
    ];

    public function house()
    {
        return $this->belongsTo(House::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
