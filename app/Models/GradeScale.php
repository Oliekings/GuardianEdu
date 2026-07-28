<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeScale extends Model
{
    protected $fillable = ['school_id', 'min_score', 'max_score', 'grade_name', 'remarks'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
