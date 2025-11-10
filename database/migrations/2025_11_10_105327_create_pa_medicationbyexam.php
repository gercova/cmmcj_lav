<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS PA_getMedicationByExam;");
        DB::unprepared(
            'CREATE PROCEDURE PA_getMedicationByExam(
                IN examen_id BIGINT
            )
            BEGIN
                SELECT 
                    f.descripcion as drug, 
                    em.descripcion rp,
                    em.dosis,
                    em.id
	            FROM examen_medicacion em
                INNER JOIN farmacos f ON em.farmaco_id = f.id
	            INNER JOIN examenes e ON em.examen_id = e.id
	            INNER JOIN historias h ON em.historia_id = h.id
                WHERE f.deleted_at IS NULL AND em.deleted_at IS NULL AND e.deleted_at IS NULL AND h.deleted_at IS NULL AND em.examen_id = examen_id
	            ORDER BY em.id asc;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS PA_getMedicationByExam;");
    }
};
