<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffProfile extends Model
{
    protected $fillable = [
        'user_id', 'school_id', 'staff_id', 'designation', 'department', 
        'joining_date', 'gender', 'dob', 'phone', 'emergency_contact', 
        'marital_status', 'photo', 'current_address', 'permanent_address', 
        'qualification', 'work_experience', 'basic_salary', 'epf_no', 
        'contract_type', 'work_shift', 'bank_account_title', 'bank_account_no', 
        'bank_name', 'ifsc_code', 'social_media'
    ];

    protected $casts = [
        'joining_date' => 'date',
        'dob' => 'date',
        'social_media' => 'json',
        'basic_salary' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
