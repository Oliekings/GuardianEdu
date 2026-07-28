<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Transport: Routes
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('start_location')->nullable();
            $table->string('end_location')->nullable();
            $table->timestamps();
        });

        // 2. Transport: Route Stations (Stops)
        Schema::create('route_stations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->time('stop_time')->nullable();
            $table->decimal('fee', 10, 2)->default(0);
            $table->timestamps();
        });

        // 3. Update Bus Fleets with Route ID
        Schema::table('bus_fleets', function (Blueprint $table) {
            $table->foreignId('route_id')->nullable()->after('school_id')->constrained()->onDelete('set null');
        });

        // 4. Hostel: Hostels
        Schema::create('hostels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['boys', 'girls', 'mixed'])->default('mixed');
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // 5. Hostel: Room Types
        Schema::create('hostel_room_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 6. Hostel: Rooms
        Schema::create('hostel_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hostel_id')->constrained()->onDelete('cascade');
            $table->foreignId('hostel_room_type_id')->constrained()->onDelete('cascade');
            $table->string('room_number');
            $table->integer('number_of_beds');
            $table->decimal('cost_per_bed', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('bus_fleets', function (Blueprint $table) {
            $table->dropForeign(['route_id']);
            $table->dropColumn('route_id');
        });
        Schema::dropIfExists('hostel_rooms');
        Schema::dropIfExists('hostel_room_types');
        Schema::dropIfExists('hostels');
        Schema::dropIfExists('route_stations');
        Schema::dropIfExists('routes');
    }
};
