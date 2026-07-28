<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = ['name', 'tier', 'price', 'features'];

    protected $casts = [
        'features' => 'json',
    ];
}
