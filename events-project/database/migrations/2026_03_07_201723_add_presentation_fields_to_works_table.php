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
        Schema::table('works', function (Blueprint $table) {
            $table->dateTime('presentation_date')->nullable()->after('file_path');
            $table->string('presentation_room')->nullable()->after('presentation_date');
            $table->integer('presentation_order')->nullable()->after('presentation_room');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('works', function (Blueprint $table) {
            $table->dropColumn(['presentation_date', 'presentation_room', 'presentation_order']);
        });
    }
};