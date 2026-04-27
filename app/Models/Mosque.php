<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mosque extends Model
{
    protected $fillable = [
        'name',
        'address',
        'Imam_name',
        'Muazzin_name',
        'Mutawally_name',
        'phone_number',
        'comments',
    ];
}
