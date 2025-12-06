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
        DB::statement("
            CREATE OR REPLACE VIEW view_active_drugs AS
            SELECT
                um.descripcion AS unidad,
                f.descripcion AS farmaco,
                f.created_at AS created_at,
                f.id AS id
            FROM farmacos f
            JOIN unidad_medida um ON f.unidad_medida_id = um.id
            WHERE f.deleted_at IS NULL AND um.deleted_at IS NULL
            ORDER BY f.id DESC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS view_active_drugs');
    }
};
