<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            // Tambahkan kolom tipe setelah 'cardno'
            $table->enum('type', ['employee', 'guest'])->default('employee')->after('cardno');
            
            // Ubah kolom polymorphic agar bisa NULL
            $table->unsignedBigInteger('cardable_id')->nullable()->change();
            $table->string('cardable_type')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn('type');
            
            // Mengembalikan seperti semula (jika perlu rollback)
            $table->unsignedBigInteger('cardable_id')->nullable(false)->change();
            $table->string('cardable_type')->nullable(false)->change();
        });
    }
};