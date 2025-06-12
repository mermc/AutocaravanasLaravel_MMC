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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('caravana_id')->constrained()->onDelete('cascade');
        $table->date('fecha_inicio');
        $table->date('fecha_fin');
        $table->decimal('precio_total', 8, 2)->default(0);   
        $table->decimal('precio_pagado', 8, 2)->default(0); 
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
