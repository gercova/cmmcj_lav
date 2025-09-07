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
        Schema::create('farmacos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('unidad_medida_id');
            $table->foreign('unidad_medida_id')->references('id')->on('unidad_medida');
            $table->string('descripcion')->unique();
            $table->string('detalle')->nullable();
            $table->float('precio')->default(0);
            $table->float('stock')->default(0);
            $table->integer('stock_min')->default(0);
            $table->integer('stock_max')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmacos');
    }
};
