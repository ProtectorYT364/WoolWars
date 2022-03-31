<?php

declare(strict_types=1);

namespace VaxPex\async;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use VaxPex\WoolWars;

class ExtractZipAsyncTask extends AsyncTask
{

    public function __construct(
        private string $worldName,
        private string $arenaName,
    )
    {}

    public function onRun() : void
    {
        $zipPath = WoolWars::getInstance()->getDataFolder() . "arenas/{$this->arenaName}/{$this->worldName}.zip";
        if(!is_file($zipPath)){
            return;
        }

        self::deleteDirectory(Server::getInstance()->getDataPath() . "worlds/{$this->worldName}");

        $zip = new \ZipArchive;
        $zip->open($zipPath);
        $zip->extractTo(Server::getInstance()->getDataPath() . "worlds");
        $zip->close();
        unset($zip);

        Server::getInstance()->getWorldManager()->loadWorld($this->worldName);
    }

    public static function deleteDirectory(string $dirPath) : bool
	{
		if (!file_exists($dirPath)) {
			return true;
		}
		if (!is_dir($dirPath)) {
			return unlink($dirPath);
		}
		foreach (scandir($dirPath) as $item) {
			if ($item == '.' || $item == '..') {
				continue;
			}
			if (!self::deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $item)) {
				return false;
			}
		}
		return rmdir($dirPath);
	}
}