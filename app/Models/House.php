<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class House extends Model
{
    protected $fillable = [
        'division_id',
        'district_id',
        'upazila_id',
        'union_id',
        'ward_id',
        'village_id',
        'house_chief_name',
        'mobile_no',
    ];

    // রিলেশনশিপসমূহ

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function upazila(): BelongsTo
    {
        return $this->belongsTo(Upazila::class);
    }

    public function union(): BelongsTo
    {
        return $this->belongsTo(Union::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

      public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
