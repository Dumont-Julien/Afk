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
        if (!isset($args[0])){
            $this->extracted($sender, $config);
        }else{
            $player = Main::getInstance()->getServer()->getPlayerByPrefix($args[0]);
            if (!$player instanceof Player) {
                $sender->sendMessage($config->get("Player_Not_Found"));
                return true;
            }
            if ($player->hasPermission("afk.bypass")){
                $sender->sendMessage($config->get("Player_ByPass_AFK"));
                return true;
            }
            $this->extracted($player, $config);
        }
        return true;
    }

    /**
     * @param Player|CommandSender $sender
     * @param Config $config
     * @return void
     */
    public function extracted(Player|CommandSender $sender, Config $config): void
    {
        if (isset(self::$afk[$sender->getName()])) {
            unset(self::$afk[$sender->getName()]);
            Server::getInstance()->broadcastMessage(str_replace("{PLAYER}", $sender->getName(), $config->get("Afk_Message_2")));
            $sender->setNameTag($sender->getNameTag());
        } else {
            self::$afk[$sender->getName()] = $sender->getName();
            Server::getInstance()->broadcastMessage(str_replace("{PLAYER}", $sender->getName(), $config->get("Afk_Message_1")));
            $sender->setNameTag("[§9AFK§f]\n" . $sender->getName());
        }
    }
}
