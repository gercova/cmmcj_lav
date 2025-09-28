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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('historia_id');
            $table->foreign('historia_id')->references('id')->on('historias');
            $table->unsignedBigInteger('estado_cita_id');
            $table->foreign('estado_cita_id')->references('id')->on('estados_cita');
            $table->date('fecha');
            $table->time('hora');
            $table->string('descripcion');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
