<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Factories\HistoryFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void {
        $this->call([
            // historias
            DocumentTypeSeeder::class,
            TipoSangreSeeder::class,
            GradoInstruccionSeeder::class,
            EstadoCivilSeeder::class,
            UbigeoRegionSeeder::class,
            UbigeoProvinciaSeeder::class,
            UbigeoDistritoSeeder::class,
            OccupationSeeder::class,
            SeguroSeeder::class,

            // exámenes
            ExamTypeSeeder::class,
            MacSeeder::class,
            UnitMeasureSeeder::class,
            DiagnosticSeeder::class,
            DrugSeeder::class,

            // Citas
            AppointmentsStatusSeeder::class,

            //Hospitalizaciones
            HospitalizationStatusSeeder::class,
            AdmissionConditionSeeder::class,
            AdmissionTypeSeeder::class,
            DischargeConditionSeeder::class,
            DischargeTypeSeeder::class,
            BedSeeder::class,
            CarefulTypeSeeder::class,
            ViaEntrySeeder::class,
            ServiceSeeder::class,

            // Seguridad
            ModuleSeeder::class,
            SubmoduleSeeder::class,
            SpecialtySeeder::class,
            ProfileSeeder::class,
            UserSeeder::class,
            RolePermissionSeeder::class,
        ]);
    }
}
