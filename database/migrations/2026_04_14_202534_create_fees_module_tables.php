<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Fee Groups
        Schema::create('fee_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Fee Types
        Schema::create('fee_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 3. Fee Masters
        Schema::create('fee_masters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('fee_group_id')->constrained()->onDelete('cascade');
            $table->foreignId('fee_type_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->date('due_date')->nullable();
            $table->timestamps();
        });

        // 4. Fee Deposits
        Schema::create('fee_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('fee_master_id')->constrained()->onDelete('cascade');
            $table->decimal('amount_paid', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('fine_amount', 15, 2)->default(0);
            $table->string('payment_mode')->default('Cash');
            $table->string('transaction_id')->nullable();
            $table->string('gateway')->nullable(); // Paystack, Stripe, etc.
            $table->foreignId('collected_by')->constrained('users')->onDelete('cascade');
            $table->date('payment_date');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_deposits');
        Schema::dropIfExists('fee_masters');
        Schema::dropIfExists('fee_types');
        Schema::dropIfExists('fee_groups');
    }
};
