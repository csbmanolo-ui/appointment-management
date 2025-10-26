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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes');//Relación con la tabla pacientes
            $table->foreignId('doctor_id')->constrained('doctores');//Relación con la tabla doctores
            $table->dateTime('fecha_hora_inicio');//Fecha y hora de inicio de la cita
            $table->dateTime('fecha_hora_fin');//Fecha y hora de fin de la cita
            $table->string('motivo_consulta');//Motivo de la consulta
            $table->string('estado')->default('pendiente'); // Ej: pendiente, confirmada, cancelada, completada
            $table->text('notas_doctor')->nullable(); // "Consultas médicas"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
