<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RpgClass;

class UserSeeder extends Seeder
{
    public function run()
    {
        $classes = ['Guerreiro', 'Mago', 'Arqueiro', 'Clérigo'];

        User::create([
            'name' => 'Elias Fonseca',
            'email' => 'elias@exemplo.com',
            'password' => bcrypt('senha123'),
            'rpg_class_id' => RpgClass::where('name', 'Guerreiro')->first()->id,
            'xp' => 100,
            'confirmed' => false,
        ]);

        User::create([
            'name' => 'Maria Souza',
            'email' => 'maria@exemplo.com',
            'password' => bcrypt('senha123'),
            'rpg_class_id' => RpgClass::where('name', 'Guerreiro')->first()->id,
            'xp' => 100,
            'confirmed' => false,
        ]);

        User::create([
            'name' => 'Carlos Oliveira',
            'email' => 'carlos@exemplo.com',
            'password' => bcrypt('senha123'),
            'rpg_class_id' => RpgClass::where('name', 'Guerreiro')->first()->id,
            'xp' => 10,
            'confirmed' => false,
        ]);

        User::create([
            'name' => 'Ana Costa',
            'email' => 'ana@exemplo.com',
            'password' => bcrypt('senha123'),
            'rpg_class_id' => RpgClass::where('name', 'Guerreiro')->first()->id,
            'xp' => 20,
            'confirmed' => false,
        ]);

        User::create([
            'name' => 'Lucas Pereira',
            'email' => 'lucas@exemplo.com',
            'password' => bcrypt('senha123'),
            'rpg_class_id' => RpgClass::where('name', 'Mago')->first()->id,
            'xp' => 50,
            'confirmed' => false,
        ]);

        User::create([
            'name' => 'Júlia Almeida',
            'email' => 'julia@exemplo.com',
            'password' => bcrypt('senha123'),
            'rpg_class_id' => RpgClass::where('name', 'Mago')->first()->id,
            'xp' => 30,
            'confirmed' => false,
        ]);

        User::create([
            'name' => 'Pedro Martins',
            'email' => 'pedro@exemplo.com',
            'password' => bcrypt('senha123'),
            'rpg_class_id' => RpgClass::where('name', 'Mago')->first()->id,
            'xp' => 20,
            'confirmed' => false,
        ]);

        User::create([
            'name' => 'Roberta Silva',
            'email' => 'roberta@exemplo.com',
            'password' => bcrypt('senha123'),
            'rpg_class_id' => RpgClass::where('name', 'Mago')->first()->id,
            'xp' => 10,
            'confirmed' => false,
        ]);

        User::create([
            'name' => 'Marcos Lima',
            'email' => 'marcos@exemplo.com',
            'password' => bcrypt('senha123'),
            'rpg_class_id' => RpgClass::where('name', 'Arqueiro')->first()->id,
            'xp' => 10,
            'confirmed' => false,
        ]);

        User::create([
            'name' => 'Camila Souza',
            'email' => 'camila@exemplo.com',
            'password' => bcrypt('senha123'),
            'rpg_class_id' => RpgClass::where('name', 'Arqueiro')->first()->id,
            'xp' => 70,
            'confirmed' => false,
        ]);

        User::create([
            'name' => 'Gabriel Costa',
            'email' => 'gabriel@exemplo.com',
            'password' => bcrypt('senha123'),
            'rpg_class_id' => RpgClass::where('name', 'Arqueiro')->first()->id,
            'xp' => 80,
            'confirmed' => false,
        ]);

        User::create([
            'name' => 'Juliana Rocha',
            'email' => 'juliana@exemplo.com',
            'password' => bcrypt('senha123'),
            'rpg_class_id' => RpgClass::where('name', 'Arqueiro')->first()->id,
            'xp' => 60,
            'confirmed' => false,
        ]);

        User::create([
            'name' => 'Renato Almeida',
            'email' => 'renato@exemplo.com',
            'password' => bcrypt('senha123'),
            'rpg_class_id' => RpgClass::where('name', 'Clérigo')->first()->id,
            'xp' => 50,
            'confirmed' => false,
        ]);

        User::create([
            'name' => 'Larissa Silva',
            'email' => 'larissa@exemplo.com',
            'password' => bcrypt('senha123'),
            'rpg_class_id' => RpgClass::where('name', 'Clérigo')->first()->id,
            'xp' => 80,
            'confirmed' => false,
        ]);

        User::create([
            'name' => 'Victor Barbosa',
            'email' => 'victor@exemplo.com',
            'password' => bcrypt('senha123'),
            'rpg_class_id' => RpgClass::where('name', 'Clérigo')->first()->id,
            'xp' => 70,
            'confirmed' => false,
        ]);
    }
}
