<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'room_id',
        'teacher_id',
        'camera_feed_id',
        'subject_name',
        'day_of_week',
        'start_time',
        'end_time'
    ];

    public function cameraFeed()
    {
        return $this->belongsTo(CameraFeed::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
