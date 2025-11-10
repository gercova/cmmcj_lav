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
        DB::unprepared("DROP PROCEDURE IF EXISTS PA_getDiagnosticsByExam;");
        DB::unprepared(
            'CREATE PROCEDURE PA_getDiagnosticsByExam(
                IN exam_id BIGINT
            )
            BEGIN
                SELECT
                    d.codigo,
                    UPPER(d.descripcion) diagnostico, 
                    ed.id
	            FROM examen_diagnostico ed
                JOIN diagnosticos d ON ed.diagnostico_id = d.id
	            JOIN examenes e ON ed.examen_id = e.id
                WHERE ed.deleted_at IS NULL AND e.deleted_at IS NULL AND e.id = exam_id
                ORDER BY ed.id ASC;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS PA_getDiagnosticsByExam;");
    }
};
