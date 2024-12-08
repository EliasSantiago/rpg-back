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
        $usersData = [
            ['name' => 'Elias Fonseca', 'email'     => 'elias@exemplo.com', 'rpg_class'     => 'Guerreiro', 'xp' => 100],
            ['name' => 'Maria Souza', 'email'       => 'maria@exemplo.com', 'rpg_class'     => 'Guerreiro', 'xp' => 100],
            ['name' => 'Carlos Oliveira', 'email'   => 'carlos@exemplo.com', 'rpg_class'    => 'Guerreiro', 'xp' => 10],
            ['name' => 'Ana Costa', 'email'         => 'ana@exemplo.com', 'rpg_class'       => 'Guerreiro', 'xp' => 20],
            ['name' => 'Lucas Pereira', 'email'     => 'lucas@exemplo.com', 'rpg_class'     => 'Mago', 'xp'      => 50],
            ['name' => 'Júlia Almeida', 'email'     => 'julia@exemplo.com', 'rpg_class'     => 'Mago', 'xp'      => 30],
            ['name' => 'Pedro Martins', 'email'     => 'pedro@exemplo.com', 'rpg_class'     => 'Mago', 'xp'      => 20],
            ['name' => 'Roberta Silva', 'email'     => 'roberta@exemplo.com', 'rpg_class'   => 'Mago', 'xp'      => 10],
            ['name' => 'Marcos Lima', 'email'       => 'marcos@exemplo.com', 'rpg_class'    => 'Arqueiro', 'xp'  => 10],
            ['name' => 'Camila Souza', 'email'      => 'camila@exemplo.com', 'rpg_class'    => 'Arqueiro', 'xp'  => 70],
            ['name' => 'Gabriel Costa', 'email'     => 'gabriel@exemplo.com', 'rpg_class'   => 'Arqueiro', 'xp'  => 80],
            ['name' => 'Juliana Rocha', 'email'     => 'juliana@exemplo.com', 'rpg_class'   => 'Arqueiro', 'xp'  => 60],
            ['name' => 'Renato Almeida', 'email'    => 'renato@exemplo.com', 'rpg_class'    => 'Clérigo', 'xp'   => 50],
            ['name' => 'Larissa Silva', 'email'     => 'larissa@exemplo.com', 'rpg_class'   => 'Clérigo', 'xp'   => 80],
            ['name' => 'Victor Barbosa', 'email'    => 'victor@exemplo.com', 'rpg_class'    => 'Clérigo', 'xp'   => 70],
            ['name' => 'João Oliveira', 'email'     => 'joao@exemplo.com', 'rpg_class'      => 'Guerreiro', 'xp' => rand(0, 100)],
            ['name' => 'Mariana Costa', 'email'     => 'mariana@exemplo.com', 'rpg_class'   => 'Mago', 'xp'      => rand(0, 100)],
            ['name' => 'Fernando Gomes', 'email'    => 'fernando@exemplo.com', 'rpg_class'  => 'Arqueiro', 'xp'  => rand(0, 100)],
            ['name' => 'Tatiane Lima', 'email'      => 'tatiane@exemplo.com', 'rpg_class'   => 'Clérigo', 'xp'   => rand(0, 100)],
            ['name' => 'Ricardo Santos', 'email'    => 'ricardo@exemplo.com', 'rpg_class'   => 'Guerreiro', 'xp' => rand(0, 100)],
            ['name' => 'Patricia Rocha', 'email'    => 'patricia@exemplo.com', 'rpg_class'  => 'Mago', 'xp'      => rand(0, 100)],
            ['name' => 'Eduardo Silva', 'email'     => 'eduardo@exemplo.com', 'rpg_class'   => 'Arqueiro', 'xp'  => rand(0, 100)],
            ['name' => 'Felipe Almeida', 'email'    => 'felipe@exemplo.com', 'rpg_class'    => 'Clérigo', 'xp'   => rand(0, 100)],
        ];

        foreach ($usersData as $userData) {
            User::create([
                'name'          => $userData['name'],
                'email'         => $userData['email'],
                'password'      => bcrypt('senha123'),
                'rpg_class_id'  => RpgClass::where('name', $userData['rpg_class'])->first()->id,
                'xp'            => $userData['xp'],
                'confirmed'     => false,
            ]);
        }
    }
}
