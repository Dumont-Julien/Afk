<?php

namespace NewTheBuilder\AFK;

use NewTheBuilder\AFK\Command\AfkCommand;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\Server;
use pocketmine\utils\Config;

class EventListener implements Listener {

    public function PlayerMoveEvent(PlayerMoveEvent $event) {

        $sender = $event->getPlayer();

        $config = new Config(Main::getInstance()->getDataFolder() . "Config.yml", Config::YAML);

        if (isset(AfkCommand::$afk[$sender->getName()])){
            unset(AfkCommand::$afk[$sender->getName()]);
            Server::getInstance()->broadcastMessage($config->get("Afk_Message_2"));
        }else{
            AfkCommand::$afk[$sender->getName()] = $sender->getName();
            Server::getInstance()->broadcastMessage("Afk_Message_1");
        }
    }

}