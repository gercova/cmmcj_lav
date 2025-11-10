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
            'ruta'          => '.',
            'icono'         => '.',
            'orden'         => 0,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        // Submódulos para Documento
        if ($documento) {
            $submodulos[] = [
                'module_id'     => $documento->id,
                'nombre'        => 'historia',
                'descripcion'   => 'Gestión de historias médicas',
                'ruta'          => 'documento.historias',
                'icono'         => 'fas fa-file-medical',
                'orden'         => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
            
            $submodulos[] = [
                'module_id'     => $documento->id,
                'nombre'        => 'examen',
                'descripcion'   => 'Gestión de exámenes médicos',
                'ruta'          => 'documento.examenes',
                'icono'         => 'fas fa-microscope',
                'orden'         => 2,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
            
            $submodulos[] = [
                'module_id'     => $documento->id,
                'nombre'        => 'hospitalizacion',
                'descripcion'   => 'Gestión de hospitalizaciones',
                'ruta'          => 'documento.hospitalizaciones',
                'icono'         => 'fas fa-procedures',
                'orden'         => 3,
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
                'ruta'          => 'mantenimiento.unidad-medida',
                'icono'         => 'fas fa-ruler',
                'orden'         => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
            
            $submodulos[] = [
                'module_id'     => $mantenimiento->id,
                'nombre'        => 'farmaco',
                'descripcion'   => 'Gestión de fármacos',
                'ruta'          => 'mantenimiento.farmacos',
                'icono'         => 'fas fa-pills',
                'orden'         => 2,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
            
            $submodulos[] = [
                'module_id'     => $mantenimiento->id,
                'nombre'        => 'diagnostico',
                'descripcion'   => 'Gestión de diagnósticos',
                'ruta'          => 'mantenimiento.diagnosticos',
                'icono'         => 'fas fa-stethoscope',
                'orden'         => 3,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
            
            $submodulos[] = [
                'module_id'     => $mantenimiento->id,
                'nombre'        => 'ocupacion',
                'descripcion'   => 'Gestión de ocupaciones',
                'ruta'          => 'mantenimiento.ocupaciones',
                'icono'         => 'fas fa-briefcase-medical',
                'orden'         => 4,
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
                'ruta'          => '.',
                'icono'         => 'fa bi bi-building-fill-gear',
                'orden'         => 1,
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
                'ruta'          => 'seguridad.especialidades',
                'icono'         => 'fas fa-user-md',
                'orden'         => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
            
            $submodulos[] = [
                'module_id'     => $seguridad->id,
                'nombre'        => 'modulo',
                'descripcion'   => 'Gestión de módulos del sistema',
                'ruta'          => 'seguridad.modulos',
                'icono'         => 'fas fa-cubes',
                'orden'         => 2,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
            
            $submodulos[] = [
                'module_id'     => $seguridad->id,
                'nombre'        => 'usuario',
                'descripcion'   => 'Gestión de usuarios del sistema',
                'ruta'          => 'seguridad.usuarios',
                'icono'         => 'fas fa-users',
                'orden'         => 3,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
            
            $submodulos[] = [
                'module_id'     => $seguridad->id,
                'nombre'        => 'permiso',
                'descripcion'   => 'Gestión de permisos del sistema',
                'ruta'          => 'seguridad.permisos',
                'icono'         => 'fas fa-user-shield',
                'orden'         => 4,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        // Insertar los submódulos
        Submodule::insert($submodulos);
        $this->command->info('Submódulos creados exitosamente!');
    }
}
