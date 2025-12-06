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
            CREATE OR REPLACE VIEW view_user_roles_last_login AS
            SELECT
                u.name AS name,
                u.email AS email,
                r.name AS rol,
                MAX(la.login_at) AS last_login,
                u.created_at AS created_at,
                u.id AS id
            FROM users u
            LEFT JOIN model_has_roles mhr  ON u.id = mhr.model_id AND mhr.model_type = 'App\\Models\\User'
            LEFT JOIN roles r ON mhr.role_id = r.id
            LEFT JOIN login_attempts la ON u.id = la.user_id
            WHERE u.deleted_at IS NULL
            group by u.id, u.name, u.email, r.name, u.created_at
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        DB::statement('DROP VIEW IF EXISTS view_user_roles_last_login');
    }
};
