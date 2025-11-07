<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UbigeosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ubigeos')->insert([
            'id_ubigeo' => '060101',
            'departamento' => 'Cajamarca',
            'provincia' => 'Cajamarca',
            'distrito' => 'Cajamarca',
        ]);
    }
}
