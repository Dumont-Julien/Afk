<?php

namespace NewTheBuilder\AFK;

use NewTheBuilder\AFK\API\AfkAPI;
use NewTheBuilder\AFK\Command\AfkCommand;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Server;
use pocketmine\utils\Config;

class EventListener implements Listener {

    /**
     * @param PlayerMoveEvent $event
     * @return void
     */
    public function PlayerMoveEvent(PlayerMoveEvent $event) {

        $sender = $event->getPlayer();

        $config = new Config(Main::getInstance()->getDataFolder() . "Config.yml", Config::YAML);

        if (isset(AfkCommand::$afk[$sender->getName()])){
            unset(AfkCommand::$afk[$sender->getName()]);
            Server::getInstance()->broadcastMessage(str_replace("{PLAYER}", $sender->getName(), $config->get("Afk_Message_2")));
            $event->cancel();
        }
    }

    /**
     * @param PlayerQuitEvent $event
     * @return void
     */
    public function PlayerQuitEvent(PlayerQuitEvent $event){
        AfkAPI::RemovePlayer($event->getPlayer());
    }

    public function PlayerKickEvent(PlayerKickEvent $event){
        AfkAPI::RemovePlayer($event->getPlayer());
    }

}