<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusFleet extends Model
{
    protected $fillable = [
        'school_id',
        'driver_id',
        'vehicle_number',
        'driver_name',
        'driver_phone',
        'current_lat',
        'current_lng',
        'heading',
        'status',
        'is_active',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
