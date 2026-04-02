<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('videos', function (Blueprint $table) {
        // Ajoute la colonne description si elle manque
        if (!Schema::hasColumn('videos', 'description')) {
            $table->text('description')->nullable()->after('video_path');
        }
        
        // Ajoute la colonne user_id pour savoir qui a posté
        if (!Schema::hasColumn('videos', 'user_id')) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->after('id');
        }

        // Rend dish_id optionnel pour permettre les "Clips généraux"
        $table->foreignId('dish_id')->nullable()->change();
    });
}

public function down()
{
    Schema::table('videos', function (Blueprint $table) {
        $table->dropColumn(['description', 'user_id']);
    });
}
};
