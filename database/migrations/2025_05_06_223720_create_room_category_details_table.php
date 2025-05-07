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
        Schema::create('room_category_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained('room_categories')->onDelete('cascade');
            $table->string('language_id')->nullable()->constrained('languages')->onDelete('cascade');
            $table->longtext('name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_category_details');
    }
};
