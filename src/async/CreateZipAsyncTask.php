<?php

declare(strict_types=1);

namespace VaxPex\async;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use VaxPex\WoolWars;

use function substr;

class CreateZipAsyncTask extends AsyncTask
{

    public function __construct(
        private string $worldName,
        private string $arenaName,
        private string $zipPath
    )
    {}

    public function onRun() : void
    {
        $server = Server::getInstance();
        $server->getWorldManager()->unloadWorld($server->getWorldManager()->getWorldByName($this->worldName));
        $zipPath = $this->zipPath;
        $worldPath = realpath($server->getDataPath() . "worlds/" . $this->worldName);
        $zip = new \ZipArchive;
        $zip->open($zipPath, $zip::CREATE | $zip::OVERWRITE);
        $files = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator($worldPath),
			\RecursiveIteratorIterator::LEAVES_ONLY
		);
        foreach($files as $data){
            if(!$data->isDir()){
                $relativePath = $this->worldName . DIRECTORY_SEPARATOR . substr($data->getRealPath(), strlen($worldPath) + 1);
                $zip->addFile($data->getRealPath(), $relativePath);
            }
        }
        $zip->close();
        unset($zip, $worldPath, $files);

        $server->getWorldManager()->loadWorld($this->worldName);
    }

    public function onCompletion() : void
    {
        WoolWars::getInstance()->getLogger()->info("Succesfully created backup for arena " . $this->arenaName);       
    }
}