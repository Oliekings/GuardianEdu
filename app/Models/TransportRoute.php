<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportRoute extends Model
{
    protected $fillable = [
        'school_id', 'name', 'start_point',
        'end_point', 'monthly_fee', 'description',
    ];

    public function assignments()
    {
        return $this->hasMany(TransportAssignment::class, 'transport_route_id');
    }
}
