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
        Schema::create('examen_heces', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('examen_id');
            $table->foreign('examen_id')->references('id')->on('examenes');
            $table->unsignedBigInteger('historia_id');
            $table->foreign('historia_id')->references('id')->on('historias');
            // Características macroscópicas
            $table->string('consistencia', 50)->nullable();
            $table->string('color', 50)->nullable();
            $table->string('mucus', 20)->nullable();
            $table->string('sangre', 20)->nullable();
            $table->string('restos_alimenticios', 100)->nullable();
            // Características microscópicas
            $table->string('leucocitos', 20)->nullable();
            $table->string('hematies', 20)->nullable();
            $table->string('bacterias', 50)->nullable();
            $table->string('levaduras', 50)->nullable();
            $table->string('parasitos', 100)->nullable();
            $table->string('huevos_parasitos', 100)->nullable();
            // Exámenes químicos
            $table->string('sangre_oculta', 20)->nullable();
            $table->decimal('ph', 3, 1)->nullable();
            $table->string('grasa_fecal', 20)->nullable();
            // Cultivo
            $table->string('cultivo_bacteriano')->nullable();
            $table->string('sensibilidad_antimicrobiana')->nullable();
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
        Schema::dropIfExists('examen_heces');
    }
};
