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
        Schema::create('resultados_anormales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('historia_id');
            $table->foreign('historia_id')->references('id')->on('historias');
            $table->enum('tipo_examen', ['Sangre', 'Orina', 'Heces']);
            $table->string('parametro', 100);
            $table->string('valor_obtenido', 100);
            $table->string('valor_referencia', 100);
            $table->enum('severidad', ['Leve', 'Moderado', 'Severo']);
            $table->text('observaciones');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultados_anormales');
    }
};
