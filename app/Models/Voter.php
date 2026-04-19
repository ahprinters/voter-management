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
        'house_name',
        'voter_number',
        'current_location',
    ];



    public function comments()
    {
        return $this->hasMany(VoterComment::class);
    }
}
