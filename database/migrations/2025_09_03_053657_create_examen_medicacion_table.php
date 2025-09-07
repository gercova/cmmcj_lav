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
        Schema::create('examen_medicacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('examen_id');
            $table->foreign('examen_id')->references('id')->on('examenes');
            $table->unsignedBigInteger('historia_id');
            $table->foreign('historia_id')->references('id')->on('historias');
            $table->unsignedBigInteger('farmaco_id');
            $table->foreign('farmaco_id')->references('id')->on('farmacos');
            $table->string('descripcion')->nullable();
            $table->string('dosis')->nullable();
            $table->string('frecuencia')->nullable();
            $table->string('duracion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examen_medicacion');
    }
};
