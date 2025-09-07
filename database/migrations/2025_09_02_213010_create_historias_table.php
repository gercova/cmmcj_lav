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
        Schema::create('historias', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tipo_documento_id');
            $table->foreign('tipo_documento_id')->references('id')->on('tipo_documento');
            $table->string('dni', 8)->unique();
            $table->string('nombres');
            $table->string('apellidos');
            $table->enum('sexo', ['F', 'M']);
            $table->date('fecha_nacimiento');
            $table->string('telefono');
            $table->string('email');
            $table->string('direccion');
            $table->unsignedInteger('grupo_sanguineo_id');
            $table->foreign('grupo_sanguineo_id')->references('id')->on('grupos_sanguineos');
            $table->unsignedInteger('grado_instruccion_id'); 
            $table->foreign('grado_instruccion_id')->references('id')->on('grados_instruccion');
            $table->string('ubigeo_nacimiento', 6)->collation('utf8mb4_general_ci');
            $table->foreign('ubigeo_nacimiento')->references('id')->on('ubigeo_distrito');
            $table->string('ubigeo_residencia', 6)->collation('utf8mb4_general_ci');
            $table->foreign('ubigeo_residencia')->references('id')->on('ubigeo_distrito');
            $table->unsignedBigInteger('ocupacion_id');
            $table->foreign('ocupacion_id')->references('id')->on('ocupaciones');
            $table->unsignedInteger('estado_civil_id');
            $table->foreign('estado_civil_id')->references('id')->on('estado_civil');
            $table->string('acompanante')->nullable();
            $table->string('acompanante_telefono')->nullable();
            $table->string('acompanante_direccion')->nullable();
            $table->string('vinculo')->nullable();
            $table->unsignedInteger('seguro_id');
            $table->foreign('seguro_id')->references('id')->on('seguros');
            $table->string('seguro_descripcion');
            $table->string('ant_quirurgicos')->nullable();
            $table->string('ant_patologicos')->nullable();
            $table->string('ant_familiares')->nullable();
            $table->string('ant_medicos')->nullable();
            $table->string('rams')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historias');
    }
};
