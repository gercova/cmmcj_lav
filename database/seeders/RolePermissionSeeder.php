<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles
        $administrador    = Role::create(['name' => 'Administrador']);
        $especialista     = Role::create(['name' => 'Especialista']);
        $asistente        = Role::create(['name' => 'Asistente']);

        // Permisos
        $crudPermissions    = ['acceder', 'ver', 'crear', 'editar', 'guardar', 'borrar'];
        $modulos            = ['historias', 'examenes', 'unidad_medida', 'farmacos', 'diagnosticos', 'ocupaciones', 'empresa', 'especialidades', 'modulos', 'usuarios', 'roles', 'permisos', 'seguridad', 'documentos', 'mantenimiento', 'reportes', 'dashboard'];
        $modules            = ['historia', 'examen', 'unidad_medida', 'farmaco', 'diagnostico', 'ocupacion', 'empresa', 'modulo', 'especialidad', 'usuario', 'rol', 'permiso'];

        foreach($modulos as $m) {
            Permission::create(['name' => $m]);
        }

        foreach ($modules as $module) {
            foreach ($crudPermissions as $action) {
                Permission::create(['name' => "{$module}_{$action}"]);
            }
        }

        $administrador  = Role::find(1);
        $especialista   = Role::find(2);
        $asistente      = Role::find(3);

        // Asignar permisos a roles
        $administrador->givePermissionTo(Permission::all());
        $especialista->givePermissionTo(Permission::where('name', 'not like', '%seguridad%')->get());
        $asistente->givePermissionTo(Permission::where('name', '%_ver')->orWhere('name', '%_crear')->get());

        $user = User::find(1);
        $user->assignRole('Administrador');
    }
}
