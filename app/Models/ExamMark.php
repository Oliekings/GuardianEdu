<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamMark extends Model
{
    protected $fillable = ['exam_schedule_id', 'student_id', 'marks_obtained', 'teacher_remarks'];

    public function schedule()
    {
        return $this->belongsTo(ExamSchedule::class, 'exam_schedule_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
