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
        Schema::create('taggables', function (Blueprint $table) {
            $table->id();
            // On s'assure que tag_id pointe explicitement vers la table tags
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');
            // Crée taggable_id (BigInt) et taggable_type (String) pour Video ou Dish
            $table->morphs('taggable'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taggables');
    }
};