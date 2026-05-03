<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'room_id',
        'name',
        'nid_no',
        'dob',
        'gender',
        'is_voter',
        'is_student',
        'occupation'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
