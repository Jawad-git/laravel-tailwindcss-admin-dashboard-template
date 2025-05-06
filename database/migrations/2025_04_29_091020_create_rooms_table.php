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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_category_id')->constrained();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->integer('floor')->nullable();
            $table->enum('status', ['available', 'occupied', 'maintenance', 'out of service']);
            $table->string('view', 72);
            $table->string('description', 72);
            $table->string('area');
            $table->dateTime('OccupationStartDate')->nullable();
            $table->dateTime('OccupationEndDate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
