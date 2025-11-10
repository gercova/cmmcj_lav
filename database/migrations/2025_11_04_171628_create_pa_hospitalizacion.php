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
        DB::unprepared("DROP PROCEDURE IF EXISTS PA_getHospitalizationsByMedicalHistory;");
        DB::unprepared(
            'CREATE PROCEDURE PA_getHospitalizationsByMedicalHistory(
                IN historia_id BIGINT
            )
            BEGIN
                SELECT hos.created_at, h.dni, CONCAT(b.description, " - ", b.floor) bed, hos.id
                FROM hospitalizaciones hos
                JOIN historias h ON hos.historia_id = h.id
                JOIN beds b ON hos.bed_id = b.id
                WHERE hos.deleted_at IS NULL AND h.deleted_at IS NULL AND b.deleted_at IS NULL AND hos.historia_id = historia_id;
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS PA_getHospitalizationsByMedicalHistory;");
    }
};
