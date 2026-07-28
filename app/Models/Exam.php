<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['school_id', 'name', 'term', 'session', 'status'];

    public function schedules()
    {
        return $this->hasMany(ExamSchedule::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
