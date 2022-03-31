<?php

declare(strict_types=1);

namespace VaxPex\utils;

use pocketmine\Server;

class Utils {

    public static function getAllWorlds()
    {
        $worlds = [];
        foreach(Server::getInstance()->getWorldManager()->getWorlds() as $world){
            $worlds[] = $world;
        }
        return $worlds;
    }
}