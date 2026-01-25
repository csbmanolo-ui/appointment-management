<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');


            $table->dateTime('fecha_hora_inicio');
            $table->dateTime('fecha_hora_fin');


            $table->string('motivo_consulta');
            $table->text('notas_doctor')->nullable();
            $table->string('estado')->default('pendiente');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
