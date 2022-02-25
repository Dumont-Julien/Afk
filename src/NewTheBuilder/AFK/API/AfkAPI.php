<?php

namespace NewTheBuilder\AFK\API;

use JetBrains\PhpStorm\Pure;
use NewTheBuilder\AFK\Command\AfkCommand;
use NewTheBuilder\AFK\Main;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class AfkAPI
{

    public static array $time;
    public static $senders;
    public static array $pos;

    /**
     * @param Player $sender
     * @return void
     */
    public static function RemovePlayer(Player $sender)
    {
        unset(self::$senders[$sender->getName()]);
        unset(self::$time[$sender->getName()]);
        unset(self::$pos[$sender->getName()]);
    }

    /**
     * @param Player $sender
     * @return void
     */
    public static function setTime(Player $sender)
    {
        self::$time[$sender->getName()] = time();
    }

    /**
     * @param Player $sender
     * @return void
     */
    public static function setPlayer(Player $sender)
    {
        self::$senders[$sender->getName()] = $sender;
    }

    /**
     * @param Player $sender
     * @return bool
     */
    public static function hasMoved(Player $sender): bool
    {
        if (self::$pos[$sender->getName()] != self::pGetPos($sender)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param Player $sender
     * @return bool
     */
    #[Pure] public static function isPlayerSet(Player $sender): bool {
        return isset(self::$senders[$sender->getName()]);
    }

    /**
     * @param Player $sender
     * @return array
     */
    public static function pGetPos(Player $sender): array {
        return [round($sender->getPosition()->getX()), round($sender->getPosition()->getY()), round($sender->getPosition()->getZ()), $sender->getWorld()];
    }

    /**
     * @param Player $sender
     * @return void
     */
    public static function pSetPos(Player $sender) {
        self::$pos[$sender->getName()] = [round($sender->getPosition()->getX()), round($sender->getPosition()->getY()), round($sender->getPosition()->getZ()), $sender->getWorld()];
    }

    /**
     * @return void
     */
    public static function checkTime() {

        if (self::$senders != NULL){
            foreach (self::$senders as $sender){
                if (isset(self::$time[$sender->getName()])) {
                    $time = self::$time[$sender->getName()];
                    if ($sender->isOnline()){
                        $config = new Config(Main::getInstance()->getDataFolder() . "Config.yml", Config::YAML);
                            if (time() - $time === (($config->get("Afk_time") - 1) * 60) and !$sender->hasPermission("afk.bypass")) {
                                $sender->sendMessage($config->get("First_Warning_AFK"));
                        }
                            if (time() - $time >= ($config->get("Afk_time") * 60)) {
                                if (!$sender->hasPermission("afk.bypass")) {
                                    $sender->kick($config->get("Kick_Reason"));
                                    self::RemovePlayer($sender);
                                }else{
                                    if (!isset(AfkCommand::$afk[$sender->getName()])){
                                        AfkCommand::$afk[$sender->getName()] = $sender->getName();
                                        Server::getInstance()->broadcastMessage(str_replace("{PLAYER}", $sender->getName(), $config->get("Afk_Message_1")));
                                        $sender->setNameTag("[§9AFK§f]\n" . $sender->getName());
                                    }
                                }
                            }
                    }
                }
            }
        }

    }
}