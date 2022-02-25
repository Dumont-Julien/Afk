<?php

namespace NewTheBuilder\AFK\Task;

use NewTheBuilder\AFK\API\AfkAPI;
use NewTheBuilder\AFK\Main;
use pocketmine\scheduler\Task;

class AfkTask extends Task {

    public function onRun(): void {
        foreach (Main::getInstance()->getServer()->getOnlinePlayers() as $sender){
            if (!isset(AfkAPI::$time[$sender->getName()])){
                AfkAPI::setTime($sender);
            }
            if (!isset(AfkAPI::$pos[$sender->getName()])){
                AfkAPI::pSetPos($sender);
            }
            if (!AfkAPI::hasMoved($sender)){
                AfkAPI::RemovePlayer($sender);
            }elseif(!AfkAPI::isPlayerSet($sender)){
                AfkAPI::setPlayer($sender);
            }
        }
        AfkAPI::checkTime();
    }

}