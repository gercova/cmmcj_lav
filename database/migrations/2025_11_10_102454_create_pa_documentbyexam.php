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
        DB::unprepared("DROP PROCEDURE IF EXISTS PA_getDocumentsByExam;");
        DB::unprepared(
            'CREATE PROCEDURE PA_getDocumentsByExam(
                IN exam_id BIGINT
            )
            BEGIN
                SELECT
                    ed.nombre_examen,
                    ed.documento, 
                    ed.fecha_examen, 
                    ed.created_at,
                    ed.id
                FROM examen_documento ed
                JOIN examenes e ON ed.examen_id = e.id
                JOIN historias h ON ed.historia_id = h.id
                WHERE ed.deleted_at IS NULL AND e.deleted_at IS NULL AND h.deleted_at IS NULL AND ed.examen_id = exam_id
	            ORDER BY ed.id ASC;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS PA_getDocumentsByExam;");
    }
};
