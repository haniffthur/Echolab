<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gates', function (Blueprint $table) {
            // Hapus kolom ip_address
            $table->dropColumn('ip_address');
            
            // Tambahkan kolom baru untuk terminal
            $table->string('terminal_in')->unique()->after('location');
            $table->string('terminal_out')->unique()->after('terminal_in');
        });
    }

    public function down(): void
    {
        Schema::table('gates', function (Blueprint $table) {
            // Kembalikan jika di-rollback
            $table->ipAddress('ip_address')->unique()->after('location');
            $table->dropColumn(['terminal_in', 'terminal_out']);
        });
    }
};