<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Expand Students Table
        Schema::table('students', function (Blueprint $table) {
            $table->string('email')->nullable()->after('last_name');
            $table->string('phone')->nullable()->after('email');
            $table->date('dob')->nullable()->after('phone');
            $table->string('gender')->nullable()->after('dob');
            $table->string('category')->nullable()->after('gender'); // e.g., General, OBC
            $table->string('religion')->nullable()->after('category');
            $table->string('blood_group', 5)->nullable()->after('religion');
            $table->date('admission_date')->nullable()->after('blood_group');
            $table->string('student_image')->nullable()->after('admission_date');

            // Parental Info
            $table->string('father_name')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('mother_occupation')->nullable();

            // Guardian Info
            $table->enum('guardian_is', ['father', 'mother', 'other'])->default('father');
            $table->string('guardian_name')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('guardian_email')->nullable();
            $table->string('guardian_relation')->nullable();
            $table->text('guardian_address')->nullable();

            $table->boolean('is_active')->default(true);
        });

        // 2. Create Staff Profiles Table
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('staff_id')->unique(); // Employee ID
            $table->string('designation')->nullable();
            $table->string('department')->nullable();
            $table->date('joining_date')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('dob')->nullable();
            $table->string('phone')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('photo')->nullable();
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->text('qualification')->nullable();
            $table->text('work_experience')->nullable();

            // Payroll/Finance
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->string('epf_no')->nullable();
            $table->string('contract_type')->nullable(); // Permanent, Probation
            $table->string('work_shift')->nullable();

            // Banking
            $table->string('bank_account_title')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('ifsc_code')->nullable();

            $table->json('social_media')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_profiles');
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'email', 'phone', 'dob', 'gender', 'category', 'religion', 'blood_group',
                'admission_date', 'student_image', 'father_name', 'father_phone',
                'father_occupation', 'mother_name', 'mother_phone', 'mother_occupation',
                'guardian_is', 'guardian_name', 'guardian_phone', 'guardian_email',
                'guardian_relation', 'guardian_address', 'is_active',
            ]);
        });
    }
};
