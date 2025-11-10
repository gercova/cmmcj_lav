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
        DB::unprepared("DROP PROCEDURE IF EXISTS PA_getExamsbyMedicalHistory;");
        DB::unprepared(
            'CREATE PROCEDURE PA_getExamsbyMedicalHistory(
                IN historia_id BIGINT
            )
            BEGIN
                SELECT 
                    e.created_at, 
                    h.dni, 
                    te.descripcion, 
                    e.id
                FROM examenes e
                JOIN tipo_examen AS te ON e.examen_tipo_id = te.id
                JOIN historias h ON e.historia_id = h.id
                WHERE e.deleted_at IS NULL AND h.deleted_at IS NULL AND e.historia_id = historia_id
                ORDER BY e.id DESC;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS PA_getExamsbyMedicalHistory;");
    }
};
