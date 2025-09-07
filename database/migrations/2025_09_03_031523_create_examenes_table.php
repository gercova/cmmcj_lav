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
        Schema::create('examenes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('historia_id');
            $table->foreign('historia_id')->references('id')->on('historias');
            $table->unsignedInteger('examen_tipo_id');
            $table->foreign('examen_tipo_id')->references('id')->on('tipo_examen');
            $table->string('pa')->nullable();
            $table->string('fc')->nullable();
            $table->string('fr')->nullable();
            $table->string('t')->nullable();
            $table->float('peso');
            $table->float('talla');
            $table->float('imc')->default(0.00);
            $table->string('motivo_consulta')->nullable();
            $table->string('m')->nullable();
            $table->string('rc')->nullable();
            $table->string('g')->nullable();
            $table->string('p')->nullable();
            $table->string('r1')->nullable();
            $table->string('u_parto')->nullable();
            $table->string('u_pap')->nullable();
            $table->string('u_ivaa')->nullable();
            $table->unsignedInteger('mac_id')->nullable();
            $table->foreign('mac_id')->references('id')->on('mac');
            $table->date('fum');
            $table->date('fpp');
            $table->string('edad_gestacional')->nullable();
            $table->string('apreciacion_general')->nullable();
            $table->string('piel_mucosas')->nullable();
            $table->string('aparato_respiratorio')->nullable();
            $table->string('cardio_vascular')->nullable();
            $table->string('abdomen')->nullable();
            $table->string('i_abm')->nullable();
            $table->string('ap_abm')->nullable();
            $table->string('c_abm')->nullable();
            $table->string('p_abm')->nullable();
            $table->string('mo_abm')->nullable();
            $table->string('io_ro_abm')->nullable();
            $table->string('genito_urinario')->nullable();
            $table->string('neurologico')->nullable();
            $table->string('au')->nullable();
            $table->string('spp')->nullable();
            $table->string('lcf')->nullable();
            $table->string('du')->nullable();
            $table->string('mf')->nullable();
            $table->string('oh')->nullable();
            $table->string('psc_prox_1')->nullable();
            $table->string('psc_prox_2')->nullable();
            $table->string('psc_prox_3')->nullable();
            $table->string('psc_prox_4')->nullable();
            $table->string('psc_prox_5')->nullable();
            $table->string('psc_prox_6')->nullable();
            $table->string('nutricion')->nullable();
            $table->string('psicologia_1')->nullable();
            $table->string('psicologia_2')->nullable();
            $table->string('psicologia_3')->nullable();
            $table->string('psicologia_4')->nullable();
            $table->string('odontologia')->nullable();
            $table->string('pezon')->nullable();
            $table->string('recomendaciones')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examenes');
    }
};
