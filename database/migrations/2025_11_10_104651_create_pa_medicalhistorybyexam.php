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
        DB::unprepared("DROP PROCEDURE IF EXISTS PA_getMedicalHistoryByExam;");
        DB::unprepared(
            'CREATE PROCEDURE PA_getMedicalHistoryByExam(
                IN examen_id BIGINT
            )
            BEGIN
                SELECT 
                    h.id history, 
                    e.id exam, 
                    UPPER(h.nombres) nombres, 
                    h.dni, 
                    (YEAR(CURRENT_DATE) - YEAR(h.fecha_nacimiento)) - (RIGHT(CURRENT_DATE,5) < RIGHT(h.fecha_nacimiento, 5)) AS edad
                FROM examenes e
                INNER JOIN historias h ON e.historia_id = h.id
                WHERE e.deleted_at IS NULL AND h.deleted_at IS NULL AND e.id = examen_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS PA_getMedicalHistoryByExam;");
    }
        
};
