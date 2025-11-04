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
        Schema::create('examen_hematologia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('examen_id');
            $table->foreign('examen_id')->references('id')->on('examenes');
            $table->unsignedBigInteger('historia_id');
            $table->foreign('historia_id')->references('id')->on('historias');
            // Hemograma completo
            $table->decimal('hemoglobina', 5, 2)->nullable();
            $table->decimal('hematocrito', 5, 2)->nullable();
            $table->decimal('leucocitos', 6, 2)->nullable();
            $table->decimal('neutrofilos', 5, 2)->nullable();
            $table->decimal('linfocitos', 5, 2)->nullable();
            $table->decimal('monocitos', 5, 2)->nullable();
            $table->decimal('eosinofilos', 5, 2)->nullable();
            $table->decimal('basofilos', 5, 2)->nullable();
            $table->decimal('plaquetas', 7, 2)->nullable();
            // Química sanguínea
            $table->decimal('glucosa', 5, 2)->nullable();
            $table->decimal('urea', 5, 2)->nullable();
            $table->decimal('creatinina', 5, 2)->nullable();
            $table->decimal('acido_urico', 5, 2)->nullable();
            $table->decimal('colesterol_total', 5, 2)->nullable();
            $table->decimal('trigliceridos', 5, 2)->nullable();
            $table->decimal('transaminasas_got', 5, 2)->nullable();
            $table->decimal('transaminasas_gpt', 5, 2)->nullable();
            $table->decimal('bilirrubina_total', 5, 2)->nullable();
            $table->decimal('bilirrubina_directa', 5, 2)->nullable();
            // Enzimas y proteínas
            $table->decimal('fosfatasa_alcalina', 5, 2)->nullable();
            $table->decimal('proteinas_totales', 5, 2)->nullable();
            $table->decimal('albumina', 5, 2)->nullable();
            $table->decimal('globulina', 5, 2)->nullable();
            // Electrolitos
            $table->decimal('sodio', 5, 2)->nullable();
            $table->decimal('potasio', 5, 2)->nullable();
            $table->decimal('cloro', 5, 2)->nullable();
            $table->decimal('calcio', 5, 2)->nullable();            
            // Marcadores
            $table->decimal('vsg', 5, 2)->nullable();
            $table->decimal('tiempo_protrombina', 5, 2)->nullable();
            $table->decimal('tpt', 5, 2)->nullable();
            $table->string('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examen_hematologia');
    }
};
