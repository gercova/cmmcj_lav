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
        // Crear la vista
        DB::statement("
            CREATE OR REPLACE VIEW view_active_stories AS
            SELECT
                CAST(h.created_at as date) AS created_at,
                h.dni AS dni,
                UPPER(h.nombres) AS nombres,
                h.fecha_nacimiento AS fecha_nacimiento,
                ((YEAR(CURDATE()) - YEAR(h.fecha_nacimiento)) - (RIGHT(CURDATE(),5) < RIGHT(h.fecha_nacimiento,5))) AS edad,
                h.sexo AS sexo,
                h.id AS id
            FROM historias h
            WHERE h.deleted_at IS NULL AND h.is_active = '1'
            ORDER BY h.id DESC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS view_active_stories');
    }
};
