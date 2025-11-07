<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('ubigeos')->insert([
            ['id_ubigeo' => '060101', 'departamento' => 'Cajamarca', 'provincia' => 'Cajamarca', 'distrito' => 'Cajamarca'],
            ['id_ubigeo' => '060102', 'departamento' => 'Cajamarca', 'provincia' => 'Cajamarca', 'distrito' => 'Baños del Inca'],
            ['id_ubigeo' => '060103', 'departamento' => 'Cajamarca', 'provincia' => 'Cajamarca', 'distrito' => 'Los Baños'],
            ['id_ubigeo' => '060104', 'departamento' => 'Cajamarca', 'provincia' => 'Celendín', 'distrito' => 'Celendín'],
            ['id_ubigeo' => '060105', 'departamento' => 'Cajamarca', 'provincia' => 'Chota', 'distrito' => 'Chota'],
        ]);
        $this->call(UbigeoSeeder::class);
        $this->call(TipoComprobanteSeeder::class);
    }
}
