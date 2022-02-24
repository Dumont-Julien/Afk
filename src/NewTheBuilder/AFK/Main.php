<?php

namespace NewTheBuilder\AFK;

use NewTheBuilder\AFK\Command\AfkCommand;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {

    private static Main $main;

    protected function onEnable(): void {
        //Listener
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        //Command
        $this->getServer()->getCommandMap()->register("Afk", new AfkCommand());
        //Config
        if (!file_exists($this->getDataFolder() . "Config.yml")) {
            $this->saveResource("Config.yml");
        }
        //API
        self::$main = $this;
    }
    public static function getInstance() : Main {
        return self::$main;
    }
}