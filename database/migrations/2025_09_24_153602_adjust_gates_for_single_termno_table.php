<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gates', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn(['terminal_in', 'terminal_out']);
            
            // Tambahkan kolom baru yang unik untuk termno
            $table->string('termno')->unique()->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('gates', function (Blueprint $table) {
            // Logika untuk rollback jika diperlukan
            $table->string('terminal_in')->unique()->after('location');
            $table->string('terminal_out')->unique()->after('terminal_in');
            $table->dropColumn('termno');
        });
    }
};