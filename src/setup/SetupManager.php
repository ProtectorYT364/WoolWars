<?php

declare(strict_types=1);

namespace VaxPex\setup;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use VaxPex\arena\Arena;
use VaxPex\form\CustomForm;
use VaxPex\form\NormalForm;
use VaxPex\utils\Utils;
use VaxPex\WoolWars;

class SetupManager
{

    public function ArraysCount()
    {
        $ArrayCount = new \ReflectionClass($this);
        $count = 0;
        for ($i = 0; $i <= count($ArrayCount->getProperties()); $i++){
            $count++;
        }
        unset($ArrayCount);
        return $count;
    }

    public static function getSetupForm(Player $player)
    {
        $form = new NormalForm(function(Player $player, $data)
        {
            if($data === null){
                return;
            }
            if($data === 0){
                foreach(Utils::getAllWorlds() as $worlds){
                    $form = new NormalForm(function(Player $player, $data)
                    {
                        if($data === null){
                            return;
                        }
                        $cooldown = 1;
                        $users = [];
                        if(!isset($users[$player->getName()])){
                            $users[$player->getName()] = time();
                            self::sendCreateForm($player, $data);
                        }
                        $cooldown--;
                        if($cooldown == 0){
                            unset($users[$player->getName()]);
                        }
                    });
                    $form->setTitle(WoolWars::PREFIX . "WorldsForm");
                    $form->addButton($worlds->getFolderName(), $worlds->getFolderName());
                    $player->sendForm($form);
                }
            }
        });
        $form->setTitle(WoolWars::PREFIX . "SetupForm");
        $form->addButton("Create Arena");
        $form->addButton("Setup Arena");
        $form->addButton("Remove Arena");
        $player->sendForm($form);
    }

    private static function sendCreateForm(Player $player, string $arenaName)
    {
        $cfg = new Config(WoolWars::getInstance()->getDataFolder() . "arenas/{$arenaName}.yml");
        $form = new CustomForm(function(Player $player, $data) use ($cfg, $arenaName)
        {
            if($data === null){
                return;
            }
            var_dump($data);
            if(isset($data[1])){
                $cfg->set("mapName", $data[1]);
            }
            if(isset($data[2])){
                $cfg->set("maxPlayers", $data[2]);
            }
            if($cfg->get("worldName") === false){
                $cfg->set("worldName", $arenaName);
            }
            $cfg->save();
            $cfg->reload();
            WoolWars::getInstance()->arenas[$arenaName] = new Arena(Server::getInstance()->getWorldManager()->getWorldByName($arenaName), WoolWars::getArenaData($data[1]));
        });
        $form->addLabel("Setup the arena");
        $form->addInput("", "Map Name");
        $form->addSlider("MaxPlayers", 2, 16);
        $player->sendForm($form);
    }
}
