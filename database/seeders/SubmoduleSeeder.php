<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use App\Models\Submodule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubmoduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {

        // Obtener los módulos existentes
        $principal      = Module::where('descripcion', 'principal')->first();
        $home           = Module::where('descripcion', 'home')->first();
        $reporte        = Module::where('descripcion', 'reporte')->first();
        $documento      = Module::where('descripcion', 'documento')->first();
        $mantenimiento  = Module::where('descripcion', 'mantenimiento')->first();
        $negocio        = Module::where('descripcion', 'negocio')->first();
        $seguridad      = Module::where('descripcion', 'seguridad')->first();

        $submodulos = [];

        Submodule::create([
            'module_id'     => $principal->id,
            'nombre'        => 'Modulo principal',
            'descripcion'   => 'Gestión del modulo principal',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        // Submódulos para Documento
        if ($documento) {
            $submodulos[] = [
                'module_id'     => $documento->id,
                'nombre'        => 'historia',
                'descripcion'   => 'Gestión de historias médicas',
                'created_at'    => now(),
                'updated_at'    => now(),
            ];

            $submodulos[] = [
                'module_id'     => $documento->id,
                'nombre'        => 'examen',
                'descripcion'   => 'Gestión de exámenes médicos',
                'created_at'    => now(),
                'updated_at'    => now(),
            ];

            $submodulos[] = [
                'module_id'     => $documento->id,
                'nombre'        => 'hospitalizacion',
                'descripcion'   => 'Gestión de hospitalizaciones',
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        // Submódulos para Mantenimiento
        if ($mantenimiento) {
            $submodulos[] = [
                'module_id'     => $mantenimiento->id,
                'nombre'        => 'unidad_medida',
                'descripcion'   => 'Gestión de unidades de medida',
                'created_at'    => now(),
                'updated_at'    => now(),
            ];

            $submodulos[] = [
                'module_id'     => $mantenimiento->id,
                'nombre'        => 'farmaco',
                'descripcion'   => 'Gestión de fármacos',
                'created_at'    => now(),
                'updated_at'    => now(),
            ];

            $submodulos[] = [
                'module_id'     => $mantenimiento->id,
                'nombre'        => 'diagnostico',
                'descripcion'   => 'Gestión de diagnósticos',
                'created_at'    => now(),
                'updated_at'    => now(),
            ];

            $submodulos[] = [
                'module_id'     => $mantenimiento->id,
                'nombre'        => 'ocupacion',
                'descripcion'   => 'Gestión de ocupaciones',
                'created_at'    => now(),
                'updated_at'    => now(),
            ];

            /*$submodulos[] = [
                'module_id'     => $mantenimiento->id,
                'nombre'        => 'empresa',
                'descripcion'   => 'Gestión de datos de la empresa',
                'ruta'          => 'mantenimiento.empresa',
                'icono'         => 'fas fa-building',
                'orden'         => 5,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];*/
        }

        if ($negocio) {
            $submodulos[] = [
                'module_id'     => $negocio->id,
                'nombre'        => 'empresa',
                'descripcion'   => 'Gestión de datos de la empresa',
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        // Submódulos para Seguridad
        if ($seguridad) {
            $submodulos[] = [
                'module_id'     => $seguridad->id,
                'nombre'        => 'especialidad',
                'descripcion'   => 'Gestión de especialidades médicas',
                'created_at'    => now(),
                'updated_at'    => now(),
            ];

            $submodulos[] = [
                'module_id'     => $seguridad->id,
                'nombre'        => 'modulo',
                'descripcion'   => 'Gestión de módulos del sistema',
                'created_at'    => now(),
                'updated_at'    => now(),
            ];

            $submodulos[] = [
                'module_id'     => $seguridad->id,
                'nombre'        => 'usuario',
                'descripcion'   => 'Gestión de usuarios del sistema',
                'created_at'    => now(),
                'updated_at'    => now(),
            ];

            $submodulos[] = [
                'module_id'     => $seguridad->id,
                'nombre'        => 'permiso',
                'descripcion'   => 'Gestión de permisos del sistema',
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        // Insertar los submódulos
        Submodule::insert($submodulos);
        $this->command->info('Submódulos creados exitosamente!');
    }
}
