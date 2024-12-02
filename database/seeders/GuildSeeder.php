<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guilds;
use App\Models\User;

class GuildSeeder extends Seeder
{
    public function run()
    {
        $guilds = [
            [
                'name' => 'Lobos Sombrios',
                'description' => 'Uma guilda focada em explorar masmorras e derrotar chefes poderosos.',
                'max_players' => 10,
                'leader_id' => 1
            ],
            [
                'name' => 'Guardião da Luz',
                'description' => 'Guilda que protege os inocentes e combate as forças das trevas.',
                'max_players' => 8,
                'leader_id' => 2
            ],
            [
                'name' => 'Caçadores de Dragões',
                'description' => 'A guilda mais temida na caça aos dragões e criaturas místicas.',
                'max_players' => 12,
                'leader_id' => 3
            ]
        ];

        foreach ($guilds as $guild) {
            Guilds::create($guild);
        }
    }
}
