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
        Schema::create('hospitalizaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('historia_id');
            $table->foreign('historia_id')->references('id')->on('historias');
            $table->unsignedInteger('cama_id');
            $table->foreign('cama_id')->references('id')->on('habitacion_cama');
            $table->string('fc')->nullable();
            $table->string('t')->nullable();
            $table->string('so2')->nullable();
            $table->dateTime('fecha_admision')->default(now());
            $table->enum('tipo_admision', ['Programada', 'Urgencia', 'Emergencia', 'Transferencia']);
            $table->enum('via_ingreso', ['Consulta Externa', 'Urgencias', 'Transferencia Externa']);
            $table->text('motivo_hospitalizacion');
            // Datos clínicos
            $table->text('alergias')->nullable();
            $table->text('medicamentos_habituales')->nullable();
            $table->text('antecedentes_importantes')->nullable();
            $table->enum('condicion_ingreso', ['Estable', 'Grave', 'Muy Grave', 'Crítico'])->nullable();
            // Ubicación y estado
            $table->enum('servicio', ['Medicina Interna', 'Cirugía', 'Pediatría', 'Obstetricia', 'Traumatología', 'Cardiología', 'Neurología', 'UCI', 'UCIN', 'Otro'])->nullable();
            $table->enum('tipo_cuidado', ['Básico', 'Intermedio', 'Intensivo', 'Monitorizado'])->nullable();
            // Datos de egreso
            $table->date('fecha_egreso')->nullable();
            $table->enum('tipo_egreso', ['Alta Médica', 'Alta Voluntaria', 'Transferencia', 'Fallecimiento', 'Otro'])->nullable();
            $table->string('diagnostico_egreso')->nullable();
            $table->enum('condicion_egreso', ['Curado', 'Mejorado', 'Sin Mejoría', 'Fallecido'])->nullable();
            $table->text('resumen_evolucion')->nullable();
            $table->string('causa_muerte')->nullable();
            // Datos administrativos
            $table->string('nro_autorizacion_seguro')->nullable();
            $table->string('aseguradora')->nullable();
            $table->enum('estado', ['Activa', 'Cerrada', 'Cancelada'])->default('Activa')->nullable();
            // Auditoría
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospitalizaciones');
    }
};
