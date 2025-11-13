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
        Schema::table('hospitalizaciones', function (Blueprint $table) {
            $table->unsignedInteger('tipo_admision_id')->after('fecha_admision');
            $table->foreign('tipo_admision_id')->references('id')->on('tipo_admision');
            $table->unsignedInteger('via_ingreso_id')->after('tipo_admision_id');
            $table->foreign('via_ingreso_id')->references('id')->on('via_ingreso');
            $table->unsignedInteger('condicion_ingreso_id')->after('antecedentes_importantes');
            $table->foreign('condicion_ingreso_id')->references('id')->on('condicion_ingreso');
            $table->unsignedInteger('servicio_id')->after('condicion_ingreso_id');
            $table->foreign('servicio_id')->references('id')->on('servicio');
            $table->unsignedInteger('tipo_cuidado_id')->after('servicio_id');
            $table->foreign('tipo_cuidado_id')->references('id')->on('tipo_cuidado');
            $table->unsignedInteger('tipo_egreso_id')->after('fecha_egreso');
            $table->foreign('tipo_egreso_id')->references('id')->on('tipo_egreso');
            $table->unsignedInteger('condicion_egreso_id')->after('diagnostico_egreso');
            $table->foreign('condicion_egreso_id')->references('id')->on('condicion_egreso');
            $table->unsignedInteger('estado_hospitalizacion_id')->after('aseguradora');
            $table->foreign('estado_hospitalizacion_id')->references('id')->on('estado_hospitalizacion');

            $table->dropColumn('tipo_admision');
            $table->dropColumn('via_ingreso');
            $table->dropColumn('condicion_ingreso');
            $table->dropColumn('servicio');
            $table->dropColumn('tipo_cuidado');
            $table->dropColumn('tipo_egreso');
            $table->dropColumn('condicion_egreso');
            $table->dropColumn('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospitalizaciones', function (Blueprint $table) {
            //
        });
    }
};
