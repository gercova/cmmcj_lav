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
        Schema::create('examen_diagnostico', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('examen_id');
            $table->foreign('examen_id')->references('id')->on('examenes');
            $table->unsignedBigInteger('historia_id');
            $table->foreign('historia_id')->references('id')->on('historias');
            $table->unsignedBigInteger('diagnostico_id');
            $table->foreign('diagnostico_id')->references('id')->on('diagnosticos');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examen_diagnostico');
    }
};
