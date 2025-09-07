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
        Schema::create('empresa', function (Blueprint $table) {
            $table->id();
            $table->string('ruc', 11)->unique();
            $table->string('razon_social');
            $table->string('nombre_comercial');
            $table->string('rubro_empresa');
            $table->string('codigo_pais');
            $table->string('telefono_comercial');
            $table->string('email_comercial');
            $table->string('pais');
            $table->string('ciudad');
            $table->string('direccion');
            $table->string('pagina_web');
            $table->string('representante_legal');
            $table->string('foto_representante');
            $table->string('logo_miniatura')->nullable();
            $table->string('logo_principal')->nullable();
            $table->string('frase_empresa')->nullable();
            $table->date('fecha_creacion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa');
    }
};
