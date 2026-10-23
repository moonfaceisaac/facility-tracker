<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Location;

class Equipment extends Model
{
    protected $fillable = [
        'asset_code',
        'name',
        'category',
        'status',
        'location_id',
        'description',
        'purchase_date',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}