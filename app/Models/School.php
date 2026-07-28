<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = ['name', 'slug', 'settings', 'is_active'];

    protected $casts = [
        'settings' => 'json',
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(SchoolSubscription::class)->where('status', 'active')->latest();
    }
}
