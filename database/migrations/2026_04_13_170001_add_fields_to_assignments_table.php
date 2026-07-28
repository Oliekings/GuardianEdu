<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->string('type')->default('assignment')->after('title'); // assignment, test, quiz
            $table->string('room_id')->nullable()->after('type');
            $table->unsignedInteger('time_limit_minutes')->nullable()->after('total_points');
            $table->boolean('is_published')->default(false)->after('time_limit_minutes');
            $table->json('questions')->nullable()->after('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn(['type', 'room_id', 'time_limit_minutes', 'is_published', 'questions']);
        });
    }
};
