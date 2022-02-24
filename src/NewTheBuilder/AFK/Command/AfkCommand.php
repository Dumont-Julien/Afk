<?php

namespace NewTheBuilder\AFK\Command;

use NewTheBuilder\AFK\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class AfkCommand extends Command {

    public static array $afk = [];

    public function __construct()
    {
        parent::__construct("afk", "Enable/Disable afk", "/afk");
        $this->setPermission("afk.command.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {

        $config = new Config(Main::getInstance()->getDataFolder() . "Config.yml", Config::YAML);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("Console_Command"));
            return true;
        }
        if (!$sender->hasPermission("afk.command.use")) {
            $sender->sendMessage($config->get("NoPermission"));
            return true;
        }
        if (isset(self::$afk[$sender->getName()])) {
            unset(self::$afk[$sender->getName()]);
            Server::getInstance()->broadcastMessage($config->get("Afk_Message_2"));
        }else{
            self::$afk[$sender->getName()] = $sender->getName();
            Server::getInstance()->broadcastMessage("Afk_Message_1");
        }
        return true;
    }

}
