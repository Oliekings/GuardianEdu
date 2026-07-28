<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Library: Books
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('author');
            $table->string('isbn')->nullable();
            $table->string('publisher')->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('available_quantity')->default(0);
            $table->decimal('price', 10, 2)->nullable();
            $table->string('rack_number')->nullable();
            $table->timestamps();
        });

        // 2. Library: Book Issues
        Schema::create('book_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('issue_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['issued', 'returned', 'lost'])->default('issued');
            $table->timestamps();
        });

        // 3. Inventory: Item Categories
        Schema::create('item_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 4. Inventory: Items
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('unit')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 5. Inventory: Item Stock
        Schema::create('item_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->enum('type', ['add', 'subtract'])->default('add');
            $table->date('date');
            $table->string('source')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_stock');
        Schema::dropIfExists('items');
        Schema::dropIfExists('item_categories');
        Schema::dropIfExists('book_issues');
        Schema::dropIfExists('books');
    }
};
