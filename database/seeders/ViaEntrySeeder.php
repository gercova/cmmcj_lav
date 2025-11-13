<?php

namespace Database\Seeders;

use App\Models\ViaEntry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ViaEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ViaEntry::create(['nombre' => 'Consulta Externa']);
        ViaEntry::create(['nombre' => 'Urgencias']);
        ViaEntry::create(['nombre' => 'Transferencia Externa']);
    }
}
