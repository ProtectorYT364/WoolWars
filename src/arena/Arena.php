<?php

declare(strict_types=1);

namespace VaxPex\arena;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\world\World;

class Arena
{

    private ArenaData $data;
    private World $world;
    private array $players = [];

    public function __construct(World $world, ArenaData $data)
    {
        $this->world = $world;
        $this->data = $data;
    }

    public function join(Player $player, bool $fromRandom = false)
    {
        if(isset($this->players[$player->getName()])){
            $player->sendMessage(TextFormat::RED . "Already in arena");
            return;
        }
        if(!count($this->getPlayers()) <= $this->getArenaData()->maxPlayers){
            if($fromRandom) $player->sendMessage(TextFormat::RED . "All the arenas is full"); else $player->sendMessage(TextFormat::RED . "The arena is full"); 
            return;
        }
        $player->setHealth(20);
        $player->getHungerManager()->setFood(20);
        $this->players[$player->getName()] = $player;
    }

    /** What you will see next is getter */

    public function getArenaData() : ArenaData
    {
        return $this->data;
    }

    public function getWorld() : World
    {
        return $this->world;
    }

    public function getPlayers() : array
    {
        return $this->players;
    }
}