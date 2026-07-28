<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CameraFeed extends Model
{
    protected $fillable = [
        'school_id',
        'display_name',
        'room_id',
        'ivs_arn',
        'playback_url',
        'is_active',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
