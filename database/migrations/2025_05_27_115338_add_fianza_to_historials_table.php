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
        Schema::table('historials', function (Blueprint $table) {
            //
            $table->decimal('fianza', 8, 2)->default(150)->after('precio_pagado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historials', function (Blueprint $table) {
            //
            $table->dropColumn('fianza');
        });
    }
};
