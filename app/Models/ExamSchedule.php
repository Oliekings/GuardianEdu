<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    protected $fillable = [
        'exam_id', 'subject_name', 'room_name', 
        'date', 'start_time', 'end_time', 
        'max_marks', 'passing_marks'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function marks()
    {
        return $this->hasMany(ExamMark::class);
    }
}
