<?php

declare(strict_types=1);

namespace VaxPex;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;
use VaxPex\arena\Arena;
use VaxPex\arena\ArenaData;
use VaxPex\commands\DefaultCommand;

use function glob;
use function basename;

class WoolWars extends PluginBase
{
    use SingletonTrait;

    public const PREFIX = TextFormat::AQUA . "Wool" . TextFormat::WHITE . "Wars > ";

    public $arenas = [];

    public function onLoad(): void
    {
        self::setInstance($this);
    }

    public function onEnable() : void
    {
        @mkdir($this->getDataFolder() . "arenas");
        $this->getLogger()->info(self::PREFIX . "Loading arenas...");
        $arenaCount = 0;
        foreach(glob($this->getDataFolder() . "arenas/*.yml") as $arenas){
            $arenaName = basename($arenas, ".yml");
            $data = self::getArenaData($arenaName);
            if($data->isCorrupted()){
                $this->getLogger()->error(self::PREFIX . "unable to load the arena {$arenaName} beacuse corrupted data");
                return;
            }
            $world = $this->getServer()->getWorldManager()->getWorldByName($data->worldName);
            $this->arenas[$arenaName] = new Arena($world, $data);
            $arenaCount++;
        }
        $this->getLogger()->info($arenaCount === 1 ? self::PREFIX . "{$arenaCount} arena loaded" : ($arenaCount === 0 ? self::PREFIX . "No arenas loaded" : self::PREFIX . "{$arenaCount} arenas loaded"));
        $this->getServer()->getCommandMap()->register(TextFormat::clean(str_replace(" > ", "", self::PREFIX), true), new DefaultCommand());
    }

    public static function getArenaData(string $arenaName) : ArenaData
    {
        $config = new Config(self::getInstance()->getDataFolder() . "arenas/{$arenaName}.yml", Config::YAML);
        $data = $config->getAll(\false);
        $arenaData = new ArenaData($data);
        $arenaData->mapName = $data["mapName"] ?? null;
        $arenaData->maxPlayers = $data["maxPlayers"] ?? null;
        $arenaData->worldName = $data["worldName"] ?? null;
        return $arenaData;
    }
}