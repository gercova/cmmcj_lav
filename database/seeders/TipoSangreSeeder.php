<?php

namespace Database\Seeders;

use App\Models\BloodGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoSangreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BloodGroup::create(['nombre' => 'A+']);
        BloodGroup::create(['nombre' => 'A-']);
        BloodGroup::create(['nombre' => 'B+']);
        BloodGroup::create(['nombre' => 'B-']);
        BloodGroup::create(['nombre' => 'AB+']);
        BloodGroup::create(['nombre' => 'AB-']);
        BloodGroup::create(['nombre' => 'O+']);
        BloodGroup::create(['nombre' => 'O-']);
        BloodGroup::create(['nombre' => 'Sin datos']);
    }
}
