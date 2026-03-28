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
    Schema::table('restaurants', function (Blueprint $table) {
        // On vérifie si la colonne n'existe pas déjà par sécurité
        if (!Schema::hasColumn('restaurants', 'has_delivery')) {
            $table->boolean('has_delivery')->default(false)->after('description');
        }
    });
}

public function down(): void
{
    Schema::table('restaurants', function (Blueprint $table) {
        $table->dropColumn('has_delivery');
    });
}
};
