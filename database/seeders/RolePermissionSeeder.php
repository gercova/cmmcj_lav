<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use App\Models\Submodule;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles
        //$administrador    = Role::create(['name' => 'Administrador']);
        //$especialista     = Role::create(['name' => 'Especialista']);
        //$asistente        = Role::create(['name' => 'Asistente']);

        // Permisos
        $crudPermissions    = ['acceder', 'ver', 'crear', 'editar', 'guardar', 'borrar'];
        $modules            = Module::all();
        $submodules         = Submodule::get();

        foreach($modules as $m) {
            Permission::create(['name' => $m->descripcion, 'descripcion' => "El permiso sive para dar acceso a los módulos principales"]);
        }

        foreach ($submodules as $sub) {
            foreach ($crudPermissions as $action) {
                Permission::create(['name' => "{$sub->nombre}_{$action}", 'descripcion' => "El permiso sirve para asigner la función de {$action}"]); 
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
