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
    Schema::create('clips', function (Blueprint $table) {
        $table->id();
        // Links the clip to the restaurateur
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
        $table->string('title');
        $table->string('video_path'); // Path to the file in storage
        $table->string('thumbnail_path')->nullable();
        $table->text('description')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clips');
    }
};
