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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
        $table->string('customer_name');
        $table->json('items'); // On stocke les plats sous forme de JSON
        $table->decimal('total_price', 10, 2);
        $table->enum('status', ['pending', 'preparing', 'ready', 'delivered'])->default('pending');
        $table->timestamp('delivery_time');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
