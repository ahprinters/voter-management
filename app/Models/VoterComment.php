<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoterComment extends Model
{
    protected $fillable = [
        'voter_id',
        'title',
        'comment',
        'file_path',
        'file_type',
        'category',
        'is_important'
    ];

    public function voter()
    {
        return $this->belongsTo(Voter::class);
    }
}
