<?php

namespace Database\Seeders;

use App\Models\Enterprise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnterpriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Enterprise::create([
            'ruc'                   => '20000000000',
            'razon_social'          => 'Servis Medus S.A.',
            'nombre_comercial'      => 'Centro Médico de la Mujer Corazón de Jesús',
            'rubro_empresa'         => 'Clínico - Hospitalario',
            'codigo_pais'           => '+51',
            'telefono_comercial'    => '999999999',
            'email_comercial'       => 'atencion-clientes@gmail.com',
            'pais'                  => 'Perú',
            'ciudad'                => 'Tarapoto',
            'direccion'             => 'Jr. Leoncio Prado 1200',
            'pagina_web'            => 'cmmcj.com',
            'representante_legal'   => 'image.png',
            'foto_representante'    => 'image.png',
            'logo_miniatura'        => 'image.png',
            'logo_principal'        => 'image.png',
            'frase_empresa'         => 'Tu salud en nuestras manos',
            'fecha_creacion'        => '2010-02-12',
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);
    }
}
