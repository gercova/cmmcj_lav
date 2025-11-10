<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UbigeoRegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ['id' => '01', 'nombre' => 'AMAZONAS'],
            ['id' => '02', 'nombre' => 'ÁNCASH'],
            ['id' => '03', 'nombre' => 'APURÍMAC'],
            ['id' => '04', 'nombre' => 'AREQUIPA'],
            ['id' => '05', 'nombre' => 'AYACUCHO'],
            ['id' => '06', 'nombre' => 'CAJAMARCA'],
            ['id' => '07', 'nombre' => 'CALLAO'],
            ['id' => '08', 'nombre' => 'CUSCO'],
            ['id' => '09', 'nombre' => 'HUANCAVELICA'],
            ['id' => '10', 'nombre' => 'HUÁNUCO'],
            ['id' => '11', 'nombre' => 'ICA'],
            ['id' => '12', 'nombre' => 'JUNÍN'],
            ['id' => '13', 'nombre' => 'LA LIBERTAD'],
            ['id' => '14', 'nombre' => 'LAMBAYEQUE'],
            ['id' => '15', 'nombre' => 'LIMA'],
            ['id' => '16', 'nombre' => 'LORETO'],
            ['id' => '17', 'nombre' => 'MADRE DE DIOS'],
            ['id' => '18', 'nombre' => 'MOQUEGUA'],
            ['id' => '19', 'nombre' => 'PASCO'],
            ['id' => '20', 'nombre' => 'PIURA'],
            ['id' => '21', 'nombre' => 'PUNO'],
            ['id' => '22', 'nombre' => 'SAN MARTÍN'],
            ['id' => '23', 'nombre' => 'TACNA'],
            ['id' => '24', 'nombre' => 'TUMBES'],
            ['id' => '25', 'nombre' => 'UCAYALI'],
        ];

        // Insertar datos usando Query Builder
        DB::table('ubigeo_region')->insert($datos);
    }
}
