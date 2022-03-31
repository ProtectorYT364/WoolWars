<?php

declare(strict_types=1);

namespace VaxPex\queue;

use pocketmine\player\Player;

/** Player saver & queue system */
class Queue
{

    private Player $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }
}