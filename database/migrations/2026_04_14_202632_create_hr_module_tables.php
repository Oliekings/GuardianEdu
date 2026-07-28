<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Staff Leaves
        Schema::create('staff_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('leave_type'); // e.g., Sick, Casual, Earned
            $table->date('start_date');
            $table->date('end_date');
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // 2. Staff Attendance
        Schema::create('staff_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['present', 'late', 'absent', 'half_day'])->default('present');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });

        // 3. Staff Payroll
        Schema::create('staff_payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('month');
            $table->integer('year');
            $table->decimal('basic_salary', 15, 2);
            $table->json('earnings')->nullable(); // JSON object for allowances
            $table->json('deductions')->nullable(); // JSON object for taxes/fees
            $table->decimal('net_salary', 15, 2);
            $table->enum('status', ['unpaid', 'generated', 'paid'])->default('unpaid');
            $table->date('payment_date')->nullable();
            $table->string('payment_mode')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_payrolls');
        Schema::dropIfExists('staff_attendance');
        Schema::dropIfExists('staff_leaves');
    }
};
