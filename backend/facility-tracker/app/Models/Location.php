<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'building_name',
        'room_number',
        'description',
    ];
    //
    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }
}

    