<?php

declare(strict_types=1);

namespace VaxPex\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;
use VaxPex\setup\SetupManager;
use VaxPex\WoolWars;

class DefaultCommand extends Command implements PluginOwned {
    
    public function __construct()
    {
        parent::__construct("woolwars", "WoolWars", \null, ["ww"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!isset($args[0])){
            help:
            $sender->sendMessage(WoolWars::PREFIX . "Help List");
            $sender->sendMessage(TextFormat::YELLOW . str_repeat("=", 10));
            $sender->sendMessage(TextFormat::GREEN . "/{$commandLabel} help : help list");
            $sender->sendMessage(TextFormat::GREEN . "/{$commandLabel} manage : all arena management");
            $sender->sendMessage(TextFormat::YELLOW . str_repeat("=", 10));
            return;
        }
        switch($args[0]){
            case "help":
                goto help;
            case "setup":
            case "create":
            case "make":
            case "new":
            case "manage":
            case "managment":
            case "delete":
            case "del":
            case "remove":
            case "rm":
                if(!$sender instanceof Player){
                    $sender->sendMessage(WoolWars::PREFIX . "This command only can be used in game");
                    return;
                }
                SetupManager::getSetupForm($sender);
                break;
        }
    }

    public function getOwningPlugin() : WoolWars
    {
        return WoolWars::getInstance();
    }
}