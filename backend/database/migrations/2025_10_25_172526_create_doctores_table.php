<?php
//Migración para crear la tabla 'doctors' en la base de datos
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
        Schema::create('doctores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); //Relación con la tabla users
            $table->foreignId('especialidad_id')->constrained('especialidades'); //Relación con la tabla especialidads
            // $table->string('num_colegiado')->nullable(); // Campo de ejemplo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
