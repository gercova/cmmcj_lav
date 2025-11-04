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
        Schema::create('examen_orina', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('examen_id');
            $table->foreign('examen_id')->references('id')->on('examenes');
            $table->unsignedBigInteger('historia_id');
            $table->foreign('historia_id')->references('id')->on('historias');
            // Características físicas
            $table->string('color', 50)->nullable();
            $table->string('aspecto', 50)->nullable();
            $table->decimal('densidad', 5, 3)->nullable();
            $table->decimal('ph', 3, 1)->nullable();
            // Características químicas
            $table->string('proteinas', 20)->nullable();
            $table->string('glucosa', 20)->nullable();
            $table->string('cetonas', 20)->nullable();
            $table->string('bilirrubina', 20)->nullable();
            $table->string('sangre_oculta', 20)->nullable();
            $table->string('urobilinogeno', 20)->nullable();
            $table->string('nitritos', 20)->nullable();
            $table->string('leucocitos_quimico', 20)->nullable();
            // Sedimento urinario
            $table->string('leucocitos_campo', 20)->nullable();
            $table->string('hematies_campo', 20)->nullable();
            $table->string('celulas_epiteliales', 20)->nullable();
            $table->string('bacterias', 20)->nullable();
            $table->string('cristales', 100)->nullable();
            $table->string('cilindros', 100)->nullable();
            $table->string('mucus', 20)->nullable();
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
        Schema::dropIfExists('examen_orina');
    }
};
