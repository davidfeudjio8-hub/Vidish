<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void 
    {
        // On nettoie au cas où
        Schema::dropIfExists('taggables');

        Schema::create('taggables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');
            
            // Cette ligne crée AUTOMATIQUEMENT taggable_id et taggable_type
            // C'est ce que Laravel cherche dans ton erreur actuelle
            $table->morphs('taggable'); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taggables');
    }
};