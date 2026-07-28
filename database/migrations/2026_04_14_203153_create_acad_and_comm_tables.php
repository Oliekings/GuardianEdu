<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Academics: Exams
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('term')->nullable(); // e.g., Quarterly, Half Yearly
            $table->string('session')->nullable(); // e.g., 2026-27
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->timestamps();
        });

        // 2. Academics: Exam Schedules
        Schema::create('exam_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->string('subject_name');
            $table->string('room_name')->nullable();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('max_marks')->default(100);
            $table->integer('passing_marks')->default(40);
            $table->timestamps();
        });

        // 3. Academics: Exam Marks
        Schema::create('exam_marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->decimal('marks_obtained', 5, 2);
            $table->text('teacher_remarks')->nullable();
            $table->timestamps();
        });

        // 4. Communication: Private Chat
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->string('attachment_path')->nullable();
            $table->string('attachment_type')->nullable(); // image, document
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('exam_marks');
        Schema::dropIfExists('exam_schedules');
        Schema::dropIfExists('exams');
    }
};
