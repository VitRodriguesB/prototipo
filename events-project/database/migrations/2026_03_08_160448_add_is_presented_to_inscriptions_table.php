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
        Schema::table('inscriptions', function (Blueprint $table) {
            // Se já existir, ele não faz nada, se não existir ele cria.
            if (!Schema::hasColumn('inscriptions', 'presented_work')) {
                $table->boolean('presented_work')->default(false)->after('attended');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscriptions', function (Blueprint $table) {
            $table->dropColumn('presented_work');
        });
    }
};