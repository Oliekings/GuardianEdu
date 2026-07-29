<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportAssignment extends Model
{
    protected $fillable = [
        'school_id', 'student_id', 'transport_route_id', 'bus_fleet_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function route()
    {
        return $this->belongsTo(TransportRoute::class, 'transport_route_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(BusFleet::class, 'bus_fleet_id');
    }
}
