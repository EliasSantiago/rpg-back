<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guilds extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'max_players',
        'leader_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'guild_user', 'guild_id', 'user_id');
    }    
}
