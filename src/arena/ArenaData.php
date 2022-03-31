<?php

declare(strict_types=1);

namespace VaxPex\arena;

class ArenaData
{

    public function __construct(
        private array $data
    )
    {}

    public $mapName;

    public $maxPlayers;

    public $worldName;

    public function isCorrupted(){
        $issetMapName = isset($this->data["mapName"]) === true;
        $issetMaxPlayers = isset($this->data["maxPlayers"]) === true;
        $issetWorldName = isset($this->data["worldName"]) === true;
        return !($issetMapName && $issetMaxPlayers && $issetWorldName);
    }
}