<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Temple extends Model
{
    protected $fillable = [
        'name',
        'address',
        'priest_name',
        'president_name',
        'phone_number',
        'comments',
    ];
}
