<?php

namespace App\Repositories;

interface GuildRepositoryInterface
{
  public function show($guildId);
  public function getAllGuilds();
  public function store(array $data): object | null;
}