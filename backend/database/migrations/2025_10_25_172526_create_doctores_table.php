<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // IMPORTANTE: Creamos la tabla con nombre en INGLÉS 'doctors'
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('especialidad');
            $table->string('telefono');
            $table->string('email')->unique();
            $table->string('color')->default('#3788d8');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
