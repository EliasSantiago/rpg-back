<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RpgClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rpg_classes')->insert([
            ['name' => 'Guerreiro'],
            ['name' => 'Mago'],
            ['name' => 'Arqueiro'],
            ['name' => 'Clérigo'],
        ]);
    }
}
