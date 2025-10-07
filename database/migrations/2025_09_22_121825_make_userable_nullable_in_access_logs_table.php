<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('access_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('userable_id')->nullable()->change();
            $table->string('userable_type')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('access_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('userable_id')->nullable(false)->change();
            $table->string('userable_type')->nullable(false)->change();
        });
    }
};