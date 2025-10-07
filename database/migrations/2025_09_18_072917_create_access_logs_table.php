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
    Schema::create('access_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('gate_id')->constrained('gates')->onDelete('cascade');
        $table->foreignId('card_id')->constrained('cards')->onDelete('cascade');
        $table->morphs('userable'); // Membuat kolom userable_id & userable_type
        $table->timestamp('tap_time');
        $table->enum('type', ['in', 'out']);
        $table->enum('status', ['granted', 'denied'])->default('granted');
        $table->timestamp('created_at')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_logs');
    }
};
