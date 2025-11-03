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
        Schema::table('submodules', function (Blueprint $table) {
            $table->dropColumn('detalle');
            $table->string('ruta')->after('descripcion');
            $table->string('icono')->after('ruta');
            $table->integer('orden')->after('icono');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submodules', function (Blueprint $table) {
            //
        });
    }
};
