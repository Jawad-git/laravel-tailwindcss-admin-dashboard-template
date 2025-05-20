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
        Schema::table('availabilities', function (Blueprint $table) {
            // Drop the old weekday column
            $table->dropColumn('weekday');
        });

        Schema::table('availabilities', function (Blueprint $table) {
            // Add foreign key reference to weekdays table
            $table->foreignId('weekday_id')->constrained('weekdays')->after('model_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('availabilities', function (Blueprint $table) {
            $table->dropForeign(['weekday_id']);
            $table->dropColumn('weekday_id');

            // Restore the old column
            $table->unsignedTinyInteger('weekday')->after('model_type');
        });
    }
};
