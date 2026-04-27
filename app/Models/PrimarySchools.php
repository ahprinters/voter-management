<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrimarySchools extends Model
{
    protected $fillable = [
        'name',
        'address',
        'headmaster_name',
        'president_name',
        'phone_number',
        'comments',
    ];
}
