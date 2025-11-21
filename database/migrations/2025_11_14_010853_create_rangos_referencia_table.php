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
        Schema::create('rangos_referencia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('historia_id');
            $table->foreign('historia_id')->references('id')->on('historias');
            $table->string('parametro', 100);
            $table->decimal('valor_minimo', 8, 3);
            $table->decimal('valor_maximo', 8, 3);
            $table->string('unidad', 20);
            $table->enum('genero', ['M', 'F', 'Ambos']);
            $table->integer('edad_minima');
            $table->integer('edad_maxima');
            $table->text('interpretacion');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rangos_referencia');
    }
};
