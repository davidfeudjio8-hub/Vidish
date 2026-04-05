<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $blueprint) {
            // On ajoute le titre après l'id, ou au début
            $blueprint->string('title')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $blueprint) {
            $blueprint->dropColumn('title');
        });
    }
};